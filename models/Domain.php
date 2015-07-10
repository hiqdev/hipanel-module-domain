<?php
/**
 * @link    http://hiqdev.com/hipanel-module-domain
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\domain\models;

use hipanel\helpers\StringHelper;
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
            [[
                'id',
                'domain',
                'statuses',
                'name',
                'zone_id',
                'zone',
                'state',
                'block',
                'lastop',
                'seller',
                'seller_name',
                'seller_id',
                'client_id',
                'client',
                'client_name',
                'remoteid',
                'prem_expires',
                'epp_client_id',
                'nameservers',
                'created_date',
                'updated_date',
                'transfer_date',
                'expiration_date',
                'expires',
                'since',
                'operated',
                'is_holded',
                'is_freezed',
                'is_premium',
                'whois_protected',
                'is_secured',
                'autorenewal',
                'registrant',
                'admin',
                'tech',
                'billing',
                'state_label',
                'registered',
                'foa_sent_to',
                'prem_daysleft',
                'daysleft',
                'premium_autorenewal',
                'url_fwval',
                'mailval',
                'parkval',
                'is_expired',
                'expires_soon',
                'soa',
                'dns',
                'is_served',
                'counters',
            ], 'safe'],
            [['note'],                                          'safe', 'on' => ['set-note','default']],

            [['registrant','admin','tech','billing'],           'safe', 'on' => ['set-contacts']],
            [['registrant','admin','tech','billing'],           'required', 'on' => ['set-contacts']],

            [['enable'],                                        'safe', 'on' => ['set-lock','set-whois-protect']],
            [['id', 'autorenewal', 'domain'], 'safe', 'on' => 'set-autorenewal'],
            [['id', 'whois_protected', 'domain'], 'safe', 'on' => 'set-whois-protec'],
            [['id', 'is_secured', 'domain'], 'safe', 'on' => 'set-lock'],
            [['id', 'is_secured', 'domain'], 'safe', 'on' => 'set-lock'],


            [['nameservers'], 'filter', 'filter' => function($value) {
                return (mb_strlen($value) > 0 ) ? StringHelper::mexplode($value) : true;
            }, 'on' => 'set-ns'],
            [['nameservers'], 'each', 'rule' => ['url'], 'on' => 'set-ns'],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels () {
        return $this->mergeAttributeLabels([
            'epp_client_id'         => Yii::t('app', 'EPP client ID'),
            'remoteid'              => Yii::t('app', 'Remote ID'),
            'statuses'              => Yii::t('app', 'Statuses'),
            'zone_id'               => Yii::t('app', 'Zone ID'),
            'domain'                => Yii::t('app', 'Domain Name'),
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
}
