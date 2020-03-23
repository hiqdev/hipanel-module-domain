<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\models;

use DateTimeImmutable;
use Exception;
use hipanel\base\Model;
use hipanel\base\ModelTrait;
use hipanel\helpers\ArrayHelper;
use hipanel\helpers\StringHelper;
use hipanel\modules\client\models\Client;
use hipanel\modules\client\models\Contact;
use hipanel\modules\dns\models\Record;
use hipanel\modules\dns\validators\DomainPartValidator;
use hipanel\modules\domain\models\query\DomainQuery;
use hipanel\modules\domain\validators\NsValidator;
use hipanel\validators\DomainValidator;
use hiqdev\hiart\ActiveQuery;
use hiqdev\hiart\ResponseErrorException;
use Yii;

/**
 * Class Domain
 *
 * @property string $domain
 * @property string $expires
 */
class Domain extends Model
{
    const STATE_OK = 'ok';
    const STATE_INCOMING = 'incoming';
    const STATE_OUTGOING = 'outgoing';
    const STATE_EXPIRED = 'expired';
    const STATE_PREINCOMING = 'preincoming';
    const STATE_DELETING = 'deleting';
    const STATE_DELETED = 'deleted';
    const STATE_GONE = 'gone';

    const DEFAULT_ZONE = 'com';

    const SEND_TO_FORCE_EMAIL = 'force_email';
    const SEND_TO_WHOIS_EMAIL = 'whois';
    const SEND_TO_REGISTRANT_EMAIL = 'registrant';
    const SEND_TO_CLIENT_EMAIL = 'client';

    public $authCode;

    public static $contactTypes = ['registrant', 'admin', 'tech', 'billing'];

    public static $maxDelegationPeriods = [
        'ru' => 2,
        'su' => 2,
        'рф' => 2,
        '*' => 10,
    ];

    public static $maxDelegations = [
        'ru' => 1,
        'su' => 1,
        'рф' => 1,
        '*' => 10,
    ];

    public static function contactTypesWithLabels()
    {
        return [
            'registrant' => Yii::t('hipanel:domain', 'Registrant contact'),
            'admin' => Yii::t('hipanel:domain', 'Admin contact'),
            'tech' => Yii::t('hipanel:domain', 'Tech contact'),
            'billing' => Yii::t('hipanel:domain', 'Billing contact'),
        ];
    }

    public static function stateOptions()
    {
        $out = [
            self::STATE_OK => Yii::t('hipanel:domain', 'Domains in «ok» state'),
            self::STATE_INCOMING => Yii::t('hipanel:domain', 'Incoming transfer domains'),
            self::STATE_OUTGOING => Yii::t('hipanel:domain', 'Outgoing transfer domains'),
            self::STATE_EXPIRED => Yii::t('hipanel:domain', 'Expired domains'),
        ];
        if (self::can('support')) {
            $out = array_merge($out, [
                self::STATE_DELETED => Yii::t('hipanel:domain', 'Deleted'),
                self::STATE_DELETING => Yii::t('hipanel:domain', 'Deleting'),
                self::STATE_PREINCOMING => Yii::t('hipanel:domain', 'FOA sent'),
                self::STATE_GONE => Yii::t('hipanel:domain', 'Transferred out'),
            ]);
        }

        return $out;
    }

    public function getStateTitle()
    {
        $options = [
            self::STATE_INCOMING => Yii::t('hipanel:domain', 'Domain name transfer from another registrar is in progress. It may take up 5 days, so keep patience.'),
            self::STATE_OUTGOING => Yii::t('hipanel:domain', 'Domain name transfer to another registrar is in progress. It may take up 5 days, so keep patience.'),
            self::STATE_EXPIRED => Yii::t('hipanel:domain', 'This domain has been expired. You have a month from the date of its expiration to renew it, otherwise it will be deleted and may be registered by someone else.'),
            self::STATE_DELETED => Yii::t('hipanel:domain', 'The domain name has been deleted because it was not renewed during the month after the expiration date. If you want to get your domain back, you should register it again.'),
            self::STATE_PREINCOMING => Yii::t('hipanel:domain', 'Domain name transfer from another registrar is requested. Check your email and confirm transfer, then wait for status change.'),
            self::STATE_GONE => Yii::t('hipanel:domain', 'Domain name has been transferred to another registrar.'),
            self::STATE_DELETING => Yii::t('hipanel:domain', 'The domain name has been expired and you have not renewed it during the month after the expiration date. Domain name will be dropped in 2 months after the expiration date, after that it may be occupied by someone else. Domain names usually got occupied by bots in minutes after dropping, then sold for many times higher price on auctions. If you don\'t want to miss your domain name, contact support to restore it.'),
        ];

        return isset($options[$this->state]) ? $options[$this->state] : null;
    }

    use ModelTrait;

    /** {@inheritdoc} */
    public function rules()
    {
        return [
            [['id', 'zone_id', 'seller_id', 'client_id', 'remoteid', 'daysleft', 'prem_daysleft', 'add_grace_period'], 'integer'],
            [['domain', 'statuses', 'name', 'zone', 'state', 'lastop', 'state_label'], 'safe'],
            [['seller', 'seller_name', 'client', 'client_name'], 'safe'],
            [['premium_expires', 'premium_days_left'], 'safe'],
            [['created_date', 'updated_date', 'transfer_date', 'expiration_date', 'expires', 'since', 'prem_expires'], 'date'],
            [['registered', 'operated'], 'date'],
            [['is_expired', 'is_served', 'is_holded', 'is_premium', 'is_secured', 'is_freezed', 'wp_freezed', 'is_wp_paid'], 'boolean'],
            [['premium_autorenewal', 'expires_soon', 'autorenewal', 'whois_protected'], 'boolean'],
            [['foa_sent_to'], 'email'],
            [['url_fwval', 'mailval', 'parkval', 'soa', 'dns', 'counters'], 'safe'],
            [['block', 'epp_client_id', 'nameservers', 'nsips', 'request_date', 'name'], 'safe'],
            [['note'], 'safe', 'on' => ['set-note', 'default']],

            // Contacts
            [['registrant_id', 'admin_id', 'tech_id', 'billing_id'], 'integer', 'on' => ['set-contacts', 'bulk-set-contacts']],
            [['registrant_id', 'admin_id', 'tech_id', 'billing_id'], 'required', 'on' => ['set-contacts']],

            [['enable'], 'safe', 'on' => ['set-lock', 'set-whois-protect']],
            [['domain', 'autorenewal'], 'safe', 'on' => 'set-autorenewal'],
            [['domain', 'whois_protected'], 'safe', 'on' => 'set-whois-protect'],
            [['domain', 'is_secured'], 'safe', 'on' => 'set-lock'],
            [['domain'], 'safe', 'on' => ['sync', 'only-object']],
            [['id'], 'required', 'on' => [
                'enable-freeze',
                'disable-freeze',
                'sync',
                'regen-password',
                'set-note',
                'set-autorenewal',
                'set-whois-protect',
                'set-lock',
                'push-with-pincode',
                'enable-hold',
                'disable-hold',
                'enable-w-p-freeze',
                'disable-w-p-freeze',
                'notify-transfer-in',
                'delete',
                'delete-agp',
                'delete-in-db',
                'force-reject-preincoming',
                'force-approve-preincoming',
                'force-notify-transfer-in',
                'reject-transfer',
            ]],

            // Check domain
            [['domain'], DomainPartValidator::class, 'enableIdn' => true, 'message' => Yii::t('hipanel:domain', '\'{value}\' is not valid domain name'), 'on' => ['check-domain']],
            [['domain'], 'filter', 'filter' => function ($value) {
                if (strpos($value, '.') !== false) {
                    return substr($value, 0, strpos($value, '.'));
                } else {
                    return $value;
                }
            }, 'on' => 'check-domain'],
            [['domain'], 'required', 'on' => ['check-domain', 'force-reject-preincoming', 'force-approve-preincoming', 'force-notify-transfer-in']],
            [['zone'], 'safe', 'on' => ['check-domain']],
            [['zone'], 'trim', 'on' => ['check-domain']],
            [['zone'], 'default', 'value' => static::DEFAULT_ZONE, 'on' => ['check-domain']],
            [['is_available'], 'boolean', 'on' => ['check-domain']],
            [['resource'], 'safe', 'on' => ['check-domain']], /// Array inside. Should be a relation hasOne

            // Domain transfer
            [['domain', 'password'], 'required', 'when' => function ($model) {
                return empty($model->domains);
            }, 'on' => ['transfer']],
            [['domains'], 'required', 'when' => function ($model) {
                return empty($model->domain) && empty($model->password);
            }, 'on' => ['transfer']],
            [['domain'], DomainValidator::class, 'enableIdn' => true, 'on' => ['transfer']],
            [['domain', 'password'], 'trim', 'on' => ['transfer']],
            [['force_email'], 'filter', 'filter' => function ($value) use ($model) {
                return isset($model->send_to) && $model->send_to === Domain::SEND_TO_FORCE_EMAIL ? $value : null;
            }, 'on' => ['force-notify-transfer-in']],
            [['force_email'], 'email', 'on' => ['force-notify-transfer-in']],
            [['force_email'], 'required', 'when' => function ($model) {
                return isset($model->send_to) && $model->send_to === Domain::SEND_TO_FORCE_EMAIL;
            }, 'on' => ['force-notify-transfer-in']],
            [['send_to'], 'safe', 'on' => ['force-notify-transfer-in']],

            // NSs
            [['domain', 'nameservers', 'nsips'], 'safe', 'on' => 'set-nss'],
            [['nameservers', 'nsips'], 'filter', 'filter' => function ($value) {
                return !is_array($value) ? StringHelper::mexplode($value) : $value;
            }, 'on' => 'OLD-set-ns'],
            [['nameservers'], 'each', 'rule' => [DomainValidator::class], 'on' => 'OLD-set-ns'],
            [['nsips'], 'each', 'rule' => [NsValidator::class], 'on' => 'OLD-set-ns'],

            // Get zones
            [['dumb'], 'safe', 'on' => ['get-zones']],

            // Domain push
            [['receiver'], 'required', 'on' => ['push', 'push-with-pincode']],
            [['pincode'], 'required', 'on' => ['push-with-pincode']],
            [['pincode'], function ($attribute, $params) {
                try {
                    $response = Client::perform('check-pincode', [$attribute => $this->$attribute, 'id' => Yii::$app->user->id]);
                } catch (Exception $e) {
                    $this->addError($attribute, Yii::t('hipanel:client', 'Wrong pincode'));
                }
            }, 'on' => ['push-with-pincode']],
            [['domain', 'sender', 'pincode'], 'safe', 'on' => ['push', 'push-with-pincode']],

            // Bulk set contacts
            [['id', 'domain'], 'safe', 'on' => ['bulk-set-contacts']],

            [[
                'confirm_data',
                'domains',
                'till_date',
                'what',
                'salt',
                'hash',
                'token',
            ], 'safe', 'on' => ['approve-preincoming', 'reject-preincoming']],

            // Premium package
            [['url_fw', 'mail', 'park', 'dnspremium'], 'integer'],
            ['premium_autorenewal', 'boolean'],

            [['id', 'premium_autorenewal'], 'required', 'on' => ['set-paid-feature-autorenewal']],
        ];
    }

    /** {@inheritdoc} */
    public function attributeLabels()
    {
        return $this->mergeAttributeLabels([
            'url_fw' => Yii::t('hipanel:domain', ''),
            'mail' => Yii::t('hipanel:domain', ''),
            'park' => Yii::t('hipanel:domain', ''),
            'dnspremium' => Yii::t('hipanel:domain', ''),

            'epp_client_id' => Yii::t('hipanel:domain', 'EPP client ID'),
            'remoteid' => Yii::t('hipanel', 'Remote ID'),
            'domain' => Yii::t('hipanel', 'Domain name'),
            'domain_like' => Yii::t('hipanel', 'Domain name'),
            'note' => Yii::t('hipanel', 'Notes'),
            'nameservers' => Yii::t('hipanel', 'Name Servers'),
            'transfer_date' => Yii::t('hipanel:domain', 'Transfered'),
            'expiration_date' => Yii::t('hipanel:domain', 'System Expiration Time'),
            'expires' => Yii::t('hipanel:domain', 'Paid till'),
            'since' => Yii::t('hipanel:domain', 'Since Time'),
            'lastop' => Yii::t('hipanel:domain', 'Last Operation'),
            'operated' => Yii::t('hipanel:domain', 'Last Operation Time'),
            'whois_protected' => Yii::t('hipanel:domain', 'WHOIS protect'),
            'is_secured' => Yii::t('hipanel:domain', 'Protection'),
            'is_holded' => Yii::t('hipanel:domain', 'On hold'),
            'is_freezed' => Yii::t('hipanel:domain', 'Domain changes freezed'),
            'wp_freezed' => Yii::t('hipanel:domain', 'Domain WHOIS freezed'),
            'prem_expires' => Yii::t('hipanel:domain', 'Premium expires'),
            'prem_daysleft' => Yii::t('hipanel:domain', 'Premium days left'),
            'is_premium' => Yii::t('hipanel:domain', 'Premium package'),
            'premium_autorenewal' => Yii::t('hipanel:domain', 'Premium autorenewal'),
            'url_fwval' => Yii::t('hipanel:domain', 'Url forwarding'),
            'mailval' => Yii::t('hipanel:domain', 'Mail'),
            'parkval' => Yii::t('hipanel:domain', 'Parking'),
            'daysleft' => Yii::t('hipanel:domain', 'Days left'),
            'is_expired' => Yii::t('hipanel:domain', 'Is expired'),
            'expires_soon' => Yii::t('hipanel:domain', 'Expires soon'),
            'foa_sent_to' => Yii::t('hipanel:domain', 'FOA sent'),

            // domain transfer
            'password' => Yii::t('hipanel:domain', 'Transfer (EPP) password'),

            // domain transfer
            'receiver' => Yii::t('hipanel:domain', 'Receiver'),
            'pincode' => Yii::t('hipanel:domain', 'Pincode'),

            // contacts
            'registrant' => Yii::t('hipanel:client', 'Registrant contact'),
            'admin' => Yii::t('hipanel:client', 'Admin contact'),
            'tech' => Yii::t('hipanel:client', 'Tech contact'),
            'billing' => Yii::t('hipanel:client', 'Billing contact'),
        ]);
    }

    public function getContacts()
    {
        return $this->hasMany(Contact::class, ['domain_id' => 'id']);
    }

    public function getRegistrant()
    {
        return $this->hasOne(Contact::class, ['domain_id' => 'id']);
    }

    public function getMailfws()
    {
        return $this->hasMany(Mailfw::class, ['domain_id' => 'id']);
    }

    public function getUrlfws()
    {
        return $this->hasMany(Urlfw::class, ['domain_id' => 'id']);
    }

    public function getPremium()
    {
        return $this->hasOne(Premium::class, ['domain_id' => 'id']);
    }

    public function getParking()
    {
        return $this->hasOne(Parking::class, ['domain_id' => 'id']);
    }

    public function getPaidwp(): ActiveQuery
    {
        return $this->hasOne(Paidwp::class, ['domain_id' => 'id']);
    }

    public function getAdmin()
    {
        return $this->hasOne(Contact::class, ['domain_id' => 'id']);
    }

    public function getTech()
    {
        return $this->hasOne(Contact::class, ['domain_id' => 'id']);
    }

    public function getBilling()
    {
        return $this->hasOne(Contact::class, ['domain_id' => 'id']);
    }

    public function getZone()
    {
        return static::findZone($this->domain);
    }

    public static function findZone($domain)
    {
        return substr($domain, strpos($domain, '.') + 1);
    }

    public function isFreezed()
    {
        return (bool) $this->is_freezed;
    }

    public function isWPFreezed()
    {
        return (bool) $this->wp_freezed;
    }

    public function isHolded()
    {
        return (bool) $this->is_holded;
    }

    public function isOk()
    {
        return $this->state === static::STATE_OK;
    }

    public function isExpired()
    {
        return $this->state === static::STATE_EXPIRED;
    }

    public function isDeleting()
    {
        return $this->state === static::STATE_DELETING;
    }

    public function isOutgoing()
    {
        return $this->state === static::STATE_OUTGOING;
    }

    public function isPreincoming()
    {
        return $this->state === static::STATE_PREINCOMING;
    }

    public function isIncoming()
    {
        return $this->state === static::STATE_INCOMING;
    }

    public function isActive()
    {
        return $this->isOk() || $this->isExpired();
    }

    public function scenarioActions()
    {
        return [
            'get-zones' => ['aux', 'get-zones'],
            'bulk-set-contacts' => 'set-contacts',
        ];
    }

    public function isDomainOwner()
    {
        return Yii::$app->user->is($this->client_id) || (!$this->can('resell') && $this->can('support') && Yii::$app->user->identity->seller_id === $this->client_id);
    }

    public function notDomainOwner()
    {
        return Yii::$app->user->not($this->client_id) && (
            $this->can('resell') || (
                    $this->can('support')
                &&  Yii::$app->user->identity->seller_id !== $this->client_id
            )
        );
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
        } catch (ResponseErrorException $e) {
            $response = $e->getMessage();
        }

        return $response;
    }

    public static function getCategories()
    {
        return [
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
            'nature' => [
                'flowers',
                'fishing',
                'space',
                'garden',
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
            'adult' => [
                'sexy',
                'xxx',
                'porn',
                'adult',
                'sex',
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

    public function isContactChangeable()
    {
        return !$this->isRussianZone();
    }

    public function isRussianRenewable()
    {
        return strtotime('+56 days') > strtotime($this->expires);
    }

    public function isRenewable(): bool
    {
        if ($this->isExpired()) {
            return true;
        }

        if (!$this->isActive()) {
            return false;
        }

        $maxDelegationPeriod = static::$maxDelegationPeriods[$this->getZone()] ?? static::$maxDelegationPeriods['*'];
        if (strtotime('+1 year', strtotime($this->expires)) > strtotime("+{$maxDelegationPeriod} year")) {
            return false;
        }

        return $this->isRussianRenewable() || !$this->isRussianZone();
    }

    /**
     * @return bool
     */
    public function isExpiresSoon(): bool
    {
        return $this->daysleft < 31;
    }

    public function isSynchronizable()
    {
        return $this->isActive() && !$this->isRussianZone();
    }

    public function isPushable()
    {
        return $this->isOk() && !$this->isRussianZone();
    }

    public function isForcePushable()
    {
        return $this->isExpired() || $this->isDeleting() || $this->isRussianZone();
    }

    public function isSetNSable()
    {
        return $this->isOk();
    }

    public function canBePushed()
    {
        return ($this->isPushable() && $this->can('domain.push'))
            ||  ($this->isForcePushable() && $this->can('domain.force-push'));
    }

    public function canDelete()
    {
        return $this->isActive() && $this->can('domain.delete') && !$this->isRussianZone();
    }

    public function canDeleteAGP()
    {
        return $this->add_grace_period !== null
            && $this->isOk()
            && strtotime($this->created_date) > strtotime("-{$this->add_grace_period}", time())
            && strtotime($this->expires) < strtotime('+1 year', time())
            && $this->can('manage');
    }

    public function isRussianZone()
    {
        return $this->isLastZone(['ru', 'su', 'рф']);
    }

    /**
     * Returns true if the zone is among given list of zones.
     * @param array|string $zones zone or list of zones
     * @return bool
     */
    public function isZone($zones) : bool
    {
        $zone = $this->getZone();
        return is_array($zones) ? in_array($zone, $zones, true) : $zone === $zones;
    }

    /**
     * Returns true if the domain zone finishes with any of given zone.
     * @param array|string $zones zone or list of zones
     * @return bool
     */
    public function isLastZone($zones) : bool
    {
        if (!is_array($zones)) {
            $zones = [$zones];
        }
        foreach ($zones as $zone) {
            if (substr($this->domain, -(strlen($zone) + 1)) === ".$zone") {
                return true;
            }
        }
        return false;
    }

    public static function can($permission)
    {
        return Yii::$app->user->can($permission);
    }

    public function canRenew()
    {
        return $this->can('domain.pay') && $this->isRenewable();
    }

    public function canRegenPassword()
    {
        return $this->isActive() && !$this->isRussianZone();
    }

    public function canSendFOA()
    {
        return $this->isPreincoming() && !$this->isRussianZone();
    }

    public function canForceSendFOA()
    {
        return $this->canSendFOA() && $this->can('domain.force-send-foa');
    }

    public function canApproveTransfer()
    {
        return $this->isOutgoing()
            && $this->can('support')
            && $this->notDomainOwner()
            && !$this->isRussianZone();
    }

    public function canCancelPreincoming()
    {
        return $this->isPreincoming()
            && $this->notDomainOwner()
            && $this->can('support')
            && !$this->isRussianZone();
    }

    public function canRejectTransfer()
    {
        return $this->isOutgoing() && !$this->isRussianZone();
    }

    public function canCancelTransfer()
    {
        return $this->isIncoming() && !$this->isRussianZone();
    }

    public function canSynchronizeContacts()
    {
        return $this->isSynchronizable()
            && $this->notDomainOwner()
            && $this->can('support');
    }

    public function canFreezeUnfreeze()
    {
        return $this->can($this->isFreezed() ? 'domain.unfreeze' : 'domain.freeze')
            && $this->notDomainOwner()
            && !$this->isRussianZone();
    }

    public function canWPFreezeUnfreeze()
    {
        return $this->can($this->isWPFreezed() ? 'domain.unfreeze' : 'domain.freeze')
            && $this->notDomainOwner()
            && !$this->isRussianZone();
    }

    public function canHoldUnhold()
    {
        return $this->isActive()
            && $this->can($this->isHolded() ? 'domain.hold' : 'domain.unhold')
            && $this->notDomainOwner()
            && !$this->isRussianZone();
    }

    public function isWPChangeable() : bool
    {
        return (!$this->isWPFreezed() || $this->canWPFreezeUnfreeze()) && !$this->isRussianZone();
    }

    public function isSecureChangeable() : bool
    {
        return !$this->isRussianZone();
    }

    public function getTopLevelZone() : string
    {
        return end(explode('.', $this->domain));
    }

    public function getExpires(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->expires);
    }

    public function needToPayWhoisProtect(): bool
    {
        return Yii::$app->getModule('domain')->payableWhoisProtect && (bool)$this->is_wp_paid;
    }

    public function isWhoisProtectPaid(): bool
    {
        return (bool)$this->whois_protected;
    }

    /**
     * {@inheritdoc}
     * @return DomainQuery
     */
    public static function find($options = []): DomainQuery
    {
        return new DomainQuery(get_called_class(), [
            'options' => $options,
        ]);
    }
}
