<?php
/**
 * @link    http://hiqdev.com/hipanel-module-domain
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\domain\models;

use Exception;
use hipanel\helpers\ArrayHelper;
use hipanel\helpers\StringHelper;
use hipanel\modules\dns\models\Record;
use hipanel\modules\dns\validators\DomainPartValidator;
use hipanel\validators\DomainValidator;
use Yii;
use yii\helpers\Html;

class Domain extends \hipanel\base\Model
{
    public $authCode;

    public static $contactOptions = [
        'registrant',
        'admin',
        'tech',
        'billing',
    ];

    use \hipanel\base\ModelTrait;

    /** @inheritdoc */
    public function rules () {
        return [
            [['id', 'zone_id', 'seller_id', 'client_id', 'remoteid', 'daysleft', 'prem_daysleft'],                      'integer'],
            [['domain', 'statuses', 'name', 'zone', 'state', 'lastop', 'state_label'],                                  'safe'],
            [['seller', 'seller_name', 'client', 'client_name'],                                                        'safe'],
            [['created_date', 'updated_date', 'transfer_date', 'expiration_date', 'expires', 'since', 'prem_expires'],  'date'],
            [['registered', 'operated'],                                                                                'date'],
            [['is_expired', 'is_served', 'is_holded', 'is_freezed', 'is_premium', 'is_secured','whois_protected'],      'boolean'],
            [['premium_autorenewal', 'expires_soon', 'autorenewal'],                                                    'boolean'],
            [['foa_sent_to'],                                                                                           'email'],
            [['url_fwval' ,'mailval', 'parkval', 'soa', 'dns', 'counters'],                                             'safe'],
            [['registrant', 'admin', 'tech', 'billing'],                                                                'integer'],
            [['block', 'epp_client_id', 'nameservers'],                                                                 'safe'],
            [['note'],                                          'safe',     'on' => ['set-note','default']],

            [['registrant','admin','tech','billing'],           'safe',     'on' => ['set-contacts']],
            [['registrant','admin','tech','billing'],           'required', 'on' => ['set-contacts']],

            [['enable'],                                        'safe',     'on' => ['set-lock','set-whois-protect']],
            [['id', 'domain', 'autorenewal'],                   'safe',     'on' => 'set-autorenewal'],
            [['id', 'domain', 'whois_protected'],               'safe',     'on' => 'set-whois-protect'],
            [['id', 'domain', 'is_secured'],                    'safe',     'on' => 'set-lock'],
            [['id', 'domain'],                                  'safe',     'on' => ['sync', 'only-object']],

            // Check domain
            [['domain'], DomainPartValidator::className(), 'on' => ['check-domain']],
            [['domain', 'zone'], 'required', 'on' => ['check-domain']],
            [['zone'], 'safe', 'on' => ['check-domain']],
            [['zone'], 'trim', 'on' => ['check-domain']],
            [['is_available'], 'boolean', 'on' => ['check-domain']],
            [['resource'], 'safe', 'on' => ['check-domain']], /// Array inside. Should be a relation hasOne

            // Domain transfer
            [['domain', 'password'], 'required', 'when' => function($model) {
                return empty($model->domains);
            }, 'on' => ['transfer']],
            [['password'], 'required', 'when' => function ($model) {
                return empty($model->domains) && $model->domain;
            }, 'on' => ['transfer']],
            [['domains'], 'required', 'when' => function($model) {
                return empty($model->domain) && empty($model->password);
            }, 'on' => ['transfer']],
            [['domain'], DomainValidator::className(), 'on' => ['transfer']],
//            [['password'], function ($attribute) {
//                try {
//                    $this->perform('CheckTransfer', ['domain' => $this->domain, 'password' => $this->password]);
//                } catch (Exception $e) {
//                    $this->addError($attribute, Yii::t('app', 'Wrong code: {message}', ['message' => $e->getMessage()]));
//                }
//            }, 'when' => function ($model) {
//                return $model->domain;
//            }, 'on' => ['transfer']],
            [['domain', 'password'], 'trim', 'on' => ['transfer']],


            [['id', 'domain', 'nameservers'],                   'safe',     'on' => 'set-nss'],
            [['nameservers'], 'filter', 'filter' => function($value) {
                return (mb_strlen($value) > 0 ) ? StringHelper::mexplode($value) : [];
            }, 'on' => 'OLD-set-ns'],
            [['nameservers'], 'each', 'rule' => [DomainValidator::className()], 'on' => 'OLD-set-nss'],
            [['dumb'], 'safe', 'on' => ['get-zones']]
        ];
    }

    /** @inheritdoc */
    public function attributeLabels () {
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
            'is_freezed'            => Yii::t('app', ' label'),
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

            // Domain transfer
            'password' => Yii::t('app', ' Code'),
        ]);
    }

    public static function getZone($domain)
    {
        return substr($domain, strpos($domain,'.')+1);
    }

    public function scenarioCommands()
    {
        return [
            'get-zones' => ['aux', 'get-zones'],
        ];
    }

    public static function isDomainOwner ($model) {
         return Yii::$app->user->is($model->client_id)
             || (!Yii::$app->user->can('resell') && Yii::$app->user->can('support') && Yii::$app->user->identity->seller_id == $model->client_id);
    }

    public static function notDomainOwner ($model) {
        return Yii::$app->user->not($model->client_id) && (!Yii::$app->user->can('resell') && Yii::$app->user->can('support') && Yii::$app->user->identity->seller_id != $model->client_id);
    }

    public function getDnsRecords() {
        return $this->hasMany(Record::className(), ['hdomain_id' => 'id']);
    }

    public function getTransferDataProvider() {
        $result = [
            'success' => null,
            'error' => null,
        ];

        $this->domains = trim($this->domains);
        $list = ArrayHelper::csplit($this->domains,"\n");
        foreach ($list as $key=> $value) {
            $strCheck .= "\n$value";
            $strCheck = trim($strCheck);
            preg_match("/^([a-z0-9][0-9a-z.-]+)( +|\t+|,|;)(.*)/i", $value, $matches);
            if ($matches) {
                $domain = check::domain(trim(strtolower($matches[1])));
                if ($domain) {
                    $password = check::password(trim($matches[3]));
                    if ($password) $doms[$domain] = compact('domain','password');
                    else $dom2err[$domain] = "wrong input password";
                } else $dom2err[$value] = 'unknown error';
            } else {
                $dom2err[$value] = "empty code";
            }
        }

        return $result;
    }

    protected function checkDomainTransfer(array $data)
    {
        try {
            $response = $this->perform('CheckTransfer', $data, true);
        } catch (\yii\base\Exception $e) {
            $response = $e->errorInfo['response'];
        }
        return $response;
    }

    public function getTransferDataProviderOptions()
    {
        $result = $domains = [];
        if ($this->domains) {
            $listOfDomains = ArrayHelper::csplit($this->domains, "\n");
            foreach ($listOfDomains as $k => $v) {
                preg_match("/^([a-z0-9][0-9a-z.-]+)( +|\t+|,|;)(.*)/i", $v, $matches);
                if ($matches) {
                    $domain = trim(strtolower($matches[1]));
                    $password = trim($matches[3]);
                    $domains[$domain] = compact('domain', 'password');
                }
            }
            $i = 0;
            $response = $this->checkDomainTransfer($domains);
            foreach ($response as $k => $v) {
                if (is_array($v)) {
                    $domain = $v['domain'];
                    $password = $domains[$v['domain']]['password'];
                    $isError = isset($v['_error']);
                    $result[] = [
                        'domain' => $domain . (!$isError ? Html::hiddenInput("DomainTransferProduct[$i][name]", $domain) : ''),
                        'password' => $password . (!$isError ? Html::hiddenInput("DomainTransferProduct[$i][password]", $password) : ''),
                        'status' => !$isError,
                        'errorMessage' => $v['_error'],
                    ];
                    $i++;
                }
            }
        } else {
            $response = $this->checkDomainTransfer([$this->domain => ['domain' => $this->domain, 'password' => $this->password]]);
            $isError = isset($response[$this->domain]['_error']);
            $result[] = [
                'domain' => $this->domain . (!$isError ? Html::hiddenInput("DomainTransferProduct[0][name]", $this->domain) : ''),
                'password' => $this->password . (!$isError ? Html::hiddenInput("DomainTransferProduct[0][password]", $this->password) : ''),
                'status' => !isset($response['_error']),
                'errorMessage' => $response[$this->domain]['_error'],
            ];
        }
        return $result;
    }
}