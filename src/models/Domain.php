<?php
/**
 * @link    http://hiqdev.com/hipanel-module-domain
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\domain\models;

use hipanel\helpers\StringHelper;
use hipanel\modules\dns\models\Record;
use hipanel\modules\dns\validators\DomainPartValidator;
use hipanel\validators\DomainValidator;
use Yii;

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

}
