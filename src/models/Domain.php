<?php

/*
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\models;

use Exception;
use hipanel\helpers\ArrayHelper;
use hipanel\helpers\StringHelper;
use hipanel\modules\client\models\Client;
use hipanel\modules\dns\models\Record;
use hipanel\modules\dns\validators\DomainPartValidator;
use hipanel\modules\domain\validators\NsValidator;
use hipanel\validators\DomainValidator;
use hiqdev\hiart\ErrorResponseException;
use Yii;
use yii\helpers\Html;

class Domain extends \hipanel\base\Model
{
    const STATE_OK = 'ok';
    const STATE_INCOMING = 'incoming';
    const STATE_OUTGOING = 'outgoing';
    const STATE_EXPIRED = 'expired';

    const DEFAULT_ZONE = 'com';

    public $authCode;

    public static $contactOptions = [
        'registrant',
        'admin',
        'tech',
        'billing',
    ];

    public static function stateOptions()
    {
        return [
            self::STATE_OK => Yii::t('hipanel/domain', 'Domains in «ok» state'),
            self::STATE_INCOMING => Yii::t('hipanel/domain', 'Incoming transfer domains'),
            self::STATE_OUTGOING => Yii::t('hipanel/domain', 'Outgoing transfer domains'),
            self::STATE_EXPIRED => Yii::t('hipanel/domain', 'Expired domains'),
        ];
    }

    use \hipanel\base\ModelTrait;

    /** {@inheritdoc} */
    public function rules()
    {
        return [
            [['id', 'zone_id', 'seller_id', 'client_id', 'remoteid', 'daysleft', 'prem_daysleft'],                      'integer'],
            [['domain', 'statuses', 'name', 'zone', 'state', 'lastop', 'state_label'],                                  'safe'],
            [['seller', 'seller_name', 'client', 'client_name'],                                                        'safe'],
            [['created_date', 'updated_date', 'transfer_date', 'expiration_date', 'expires', 'since', 'prem_expires'],  'date'],
            [['registered', 'operated'],                                                                                'date'],
            [['is_expired', 'is_served', 'is_holded', 'is_premium', 'is_secured', 'is_freezed', 'wp_freezed'],          'boolean'],
            [['premium_autorenewal', 'expires_soon', 'autorenewal', 'whois_protected'],                                 'boolean'],
            [['foa_sent_to'],                                                                                           'email'],
            [['url_fwval', 'mailval', 'parkval', 'soa', 'dns', 'counters'],                                             'safe'],
            [['registrant', 'admin', 'tech', 'billing'],                                                                'integer'],
            [['block', 'epp_client_id', 'nameservers', 'nsips'],                                                        'safe'],
            [['id', 'note'],                                    'safe',     'on' => ['set-note', 'default']],

            [['registrant', 'admin', 'tech', 'billing'],           'required', 'on' => ['set-contacts']],

            [['enable'],                                        'safe',     'on' => ['set-lock', 'set-whois-protect']],
            [['id', 'domain', 'autorenewal'],                   'safe',     'on' => 'set-autorenewal'],
            [['id', 'domain', 'whois_protected'],               'safe',     'on' => 'set-whois-protect'],
            [['id', 'domain', 'is_secured'],                    'safe',     'on' => 'set-lock'],
            [['id', 'domain'],                                  'safe',     'on' => ['sync', 'only-object']],
            [['id'],                                            'integer',  'on' => ['enable-freeze', 'disable-freeze']],

            // Check domain
            [['domain'], DomainPartValidator::className(), 'on' => ['check-domain']],
            [['domain'], 'filter', 'filter' => function ($value) {
                if (strpos($value, '.') !== false) {
                    return substr($value, 0, strpos($value, '.'));
                } else {
                    return $value;
                }
            }, 'on' => 'check-domain'],
            [['domain'], 'required', 'on' => ['check-domain']],
            [['zone'], 'safe', 'on' => ['check-domain']],
            [['zone'], 'trim', 'on' => ['check-domain']],
            [['zone'], 'default', 'value' => static::DEFAULT_ZONE, 'on' => ['check-domain']],
            [['is_available'], 'boolean', 'on' => ['check-domain']],
            [['resource'], 'safe', 'on' => ['check-domain']], /// Array inside. Should be a relation hasOne

            // Domain transfer
            [['domain', 'password'], 'required', 'when' => function ($model) {
                return empty($model->domains);
            }, 'on' => ['transfer']],
            [['password'], 'required', 'when' => function ($model) {
                return empty($model->domains) && $model->domain;
            }, 'on' => ['transfer']],
            [['domains'], 'required', 'when' => function ($model) {
                return empty($model->domain) && empty($model->password);
            }, 'on' => ['transfer']],
            [['domain'], DomainValidator::className(), 'on' => ['transfer']],
            [['password'], function ($attribute) {
                try {
                    $this->perform('CheckTransfer', ['domain' => $this->domain, 'password' => $this->password]);
                } catch (Exception $e) {
                    $this->addError($attribute, Yii::t('app', 'Wrong code: {message}', ['message' => $e->getMessage()]));
                }
            }, 'when' => function ($model) {
                return $model->domain;
            }, 'on' => ['transfer']],
            [['domain', 'password'], 'trim', 'on' => ['transfer']],

            // NSs
            [['id', 'domain', 'nameservers', 'nsips'],                   'safe',     'on' => 'set-nss'],
            [['nameservers', 'nsips'], 'filter', 'filter' => function ($value) {
                return !is_array($value) ? StringHelper::mexplode($value) : $value;
            }, 'on' => 'OLD-set-ns'],
            [['nameservers'], 'each', 'rule' => [DomainValidator::className()], 'on' => 'OLD-set-ns'],
            [['nsips'], 'each', 'rule' => [NsValidator::class], 'on' => 'OLD-set-ns'],

            // Get zones
            [['dumb'], 'safe', 'on' => ['get-zones']],

            // Domain push
            [['receiver'], 'required', 'on' => ['push', 'push-with-pincode']],
            [['pincode'], 'required', 'on' => ['push-with-pincode']],
            [['pincode'], function ($attribute, $params) {
                try {
                    $response = Client::perform('CheckPincode', [$attribute => $this->$attribute, 'id' => Yii::$app->user->id]);
                } catch (Exception $e) {
                    $this->addError($attribute, Yii::t('app', 'Wrong pincode'));
                }
            }, 'on' => ['push-with-pincode']],
            [['id', 'domain', 'sender', 'pincode'], 'safe', 'on' => ['push', 'push-with-pincode']],

            // Bulk set contacts
            [['id', 'domain'], 'safe', 'on' => ['bulk-set-contacts']],
            [['registrant', 'admin', 'tech', 'billing'], 'required', 'on' => ['bulk-set-contacts']],
        ];
    }

    /** {@inheritdoc} */
    public function attributeLabels()
    {
        return $this->mergeAttributeLabels([
            'epp_client_id'         => Yii::t('app', 'EPP client ID'),
            'remoteid'              => Yii::t('app', 'Remote ID'),
            'domain'                => Yii::t('app', 'Domain Name'),
            'domain_like'           => Yii::t('app', 'Domain Name'),
            'note'                  => Yii::t('app', 'Notes'),
            'nameservers'           => Yii::t('app', 'Name Servers'),
            'created_date'          => Yii::t('app', 'Registered'),
            'updated_date'          => Yii::t('app', 'Update Time'),
            'transfer_date'         => Yii::t('app', 'Transfered'),
            'expiration_date'       => Yii::t('app', 'System Expiration Time'),
            'expires'               => Yii::t('app', 'Payed Till'),
            'since'                 => Yii::t('app', 'Since Time'),
            'lastop'                => Yii::t('app', 'Last Operation'),
            'operated'              => Yii::t('app', 'Last Operation Time'),
            'whois_protected'       => Yii::t('app', 'WHOIS'),
            'is_secured'            => Yii::t('app', 'Lock'),
            'is_holded'             => Yii::t('app', ' label'),
            'is_freezed'            => Yii::t('hipanel/domain', 'Domain changes freezed'),
            'wp_freezed'            => Yii::t('hipanel/domain', 'Domain WHOIS freezed'),
            'foa_sent_to'           => Yii::t('app', ' label'),
            'is_premium'            => Yii::t('app', ' label'),
            'prem_expires'          => Yii::t('app', ' label'),
            'prem_daysleft'         => Yii::t('app', ' label'),
            'premium_autorenewal'   => Yii::t('app', ' label'),
            'url_fwval'             => Yii::t('app', ' label'),
            'mailval'               => Yii::t('app', ' label'),
            'parkval'               => Yii::t('app', ' label'),
            'daysleft'              => Yii::t('app', ' label'),
            'is_expired'            => Yii::t('app', ' label'),
            'expires_soon'          => Yii::t('app', ' label'),

            // domain transfer
            'password'              => yii::t('app', 'auth/epp code'),

            // domain transfer
            'receiver'              => yii::t('app', 'Receiver'),
        ]);
    }

    public static function getZone($domain)
    {
        return substr($domain, strpos($domain, '.') + 1);
    }

    public function isFreezed()
    {
        return $this->is_freezed;
    }

    public function isWPFreezed()
    {
        return $this->wp_freezed;
    }

    public function scenarioCommands()
    {
        return [
            'get-zones' => ['aux', 'get-zones'],
        ];
    }

    public static function isDomainOwner($model)
    {
        return Yii::$app->user->is($model->client_id)
             || (!Yii::$app->user->can('resell') && Yii::$app->user->can('support') && Yii::$app->user->identity->seller_id === $model->client_id);
    }

    public static function notDomainOwner($model)
    {
        return Yii::$app->user->not($model->client_id) && (!Yii::$app->user->can('resell') && Yii::$app->user->can('support') && Yii::$app->user->identity->seller_id !== $model->client_id);
    }

    public function getDnsRecords()
    {
        return $this->hasMany(Record::className(), ['hdomain_id' => 'id']);
    }

    public function getTransferDataProvider()
    {
        $result = [
            'success' => null,
            'error' => null,
        ];

        $this->domains = trim($this->domains);
        $list = ArrayHelper::csplit($this->domains, "\n");
        foreach ($list as $key => $value) {
            $strCheck .= "\n$value";
            $strCheck = trim($strCheck);
            preg_match("/^([a-z0-9][0-9a-z.-]+)( +|\t+|,|;)(.*)/i", $value, $matches);
            if ($matches) {
                $domain = check::domain(trim(strtolower($matches[1])));
                if ($domain) {
                    $password = check::password(trim($matches[3]));
                    if ($password) {
                        $doms[$domain] = compact('domain', 'password');
                    } else {
                        $dom2err[$domain] = 'wrong input password';
                    }
                } else {
                    $dom2err[$value] = 'unknown error';
                }
            } else {
                $dom2err[$value] = 'empty code';
            }
        }

        return $result;
    }

    protected function checkDomainTransfer(array $data)
    {
        try {
            $response = $this->perform('CheckTransfer', $data, true);
        } catch (ErrorResponseException $e) {
            $response = $e->getMessage();
        }

        return $response;
    }

//    public function getTransferDataProviderOptions()
//    {
//            $response = $this->checkDomainTransfer($domains);
//            foreach ($response as $k => $v) {
//                if (is_array($v)) {
//                    $domain = $v['domain'];
//                    $password = $domains[$v['domain']]['password'];
//                    $isError = isset($v['_error']);
//                    $result[] = [
//                        'domain' => $domain . (!$isError ? Html::hiddenInput("DomainTransferProduct[$i][name]", $domain) : ''),
//                        'password' => $password . (!$isError ? Html::hiddenInput("DomainTransferProduct[$i][password]", $password) : ''),
//                        'status' => !$isError,
//                        'errorMessage' => $isError ? $v['_error'] : '',
//                    ];
//                    ++$i;
//                }
//            }
//
//        return $result;
//    }

    public static function getCategories()
    {
        return [
            'adult' => [
                'sexy',
                'xxx',
                'porn',
                'adult',
                'sex',
            ],
            'business' => [
                'bar',
                'auto',
                'car',
                'cars',
                'rent',
                'security',
                'tickets',
            ],
            'geo' => [
                'miami',
                'london',
                'bayern',
                'budapest',
                'ae.org',
                'africa.com',
                'ar.com',
                'br.com',
                'cn.com',
                'com.se',
                'de.com',
                'eu.com',
                'gb.com',
                'gb.net',
                'gr.com',
                'hu.com',
                'hu.net',
                'jp.net',
                'jpn.com',
                'kr.com',
                'la',
                'mex.com',
                'no.com',
                'qc.com',
                'ru.com',
                'sa.com',
                'se.com',
                'se.net',
                'uk.com',
                'uk.net',
                'us.com',
                'us.org',
                'uy.com',
                'za.com',
                'kiev.ua',
                'com.ua',
                'su',
                'cc',
                'tv',
                'me',
                'co.com',
                'com.de',
                'in.net',
                'pw',
            ],
            'general' => [
                'com',
                'net',
                'name',
                'biz',
                'org',
                'info',
                'pro',
                'mobi',
            ],
            'nature' => [
                'flowers',
                'fishing',
                'space',
                'garden',
            ],
            'internet' => [
                'lol',
                'pics',
                'hosting',
                'click',
                'link',
                'wiki',
                'website',
                'host',
                'xyz',
                'feedback',
                'online',
                'site',
            ],
            'sport' => [
                'yoga',
                'diet',
                'fit',
                'rodeo',
            ],
            'society' => [
                'property',
                'college',
                'luxe',
                'vip',
                'abogado',
                'press',
                'blackfriday',
                'law',
                'work',
                'help',
                'theatre',
            ],
            'audio_music' => [
                'guitars',
                'hiphop',
                'audio',
            ],
            'home_gifts' => [
                'mom',
                'christmas',
                'cooking',
                'wedding',
                'gift',
                'casa',
                'design',
            ],
        ];
    }

    public static function getSpecial()
    {
        return [
            'popular' => ['com', 'net', 'org', 'info', 'biz', 'ru', 'me'],
            'promotion' => ['ru', 'xxx', 'com', 'net', 'org'],
        ];
    }

    public static function setIsotopeFilterValue($zone)
    {
        $getClass = function (array $arr) use ($zone) {
            $result = '';
            foreach ($arr as $cssClass => $items) {
                if (in_array($zone, $items, true)) {
                    $result = $cssClass;
                    break;
                }
            }
            return $result;
        };

        $result = sprintf('%s %s', $getClass(self::getCategories()), $getClass(self::getSpecial()));

        return $result;
    }

    public static function getCategoriesCount($zone, $data)
    {
        $i = 0;
        $categories = self::getCategories();
        if (!empty($data)) {
            foreach ($data as $item) {
                if (in_array($item['zone'], $categories[$zone], true)) {
                    ++$i;
                }
            }
        }

        return $i;
    }

    public function canRenew()
    {
        return in_array($this->state, [static::STATE_OK, static::STATE_EXPIRED]);
    }
}
