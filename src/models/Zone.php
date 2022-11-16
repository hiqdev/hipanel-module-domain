<?php

namespace hipanel\modules\domain\models;

use hipanel\base\Model;
use hipanel\base\ModelTrait;
use hipanel\models\Ref;
use Yii;

class Zone extends Model
{
    use ModelTrait;

    const STATE_OK = 'ok';
    const STATE_NOT_WORKING = 'notworking';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer', 'on' => ['create', 'update']],
            [['name', 'registry', 'no', 'state', 'add_grace_period', 'autorenew_grace_period', 'redemption_grace_period'], 'string', 'on' => ['create', 'update']],
            [['idn'], 'string'],
            [
                ['has_contacts', 'password_required', 'whois_protect_disabled', 'sync_whois', 'wp_disabled'],
                'boolean',
                'trueValue' => '1',
                'falseValue' => '0',
                'on' => ['create', 'update'],
            ],
            [['name', 'registry', 'no', 'state'], 'required', 'on' => ['create', 'update']],
            [['id'], 'required', 'on' => ['update', 'delete']],
            [['add_grace_limit'], 'integer', 'min' => 0, 'max' => 100],
            [['max_delegation', 'max_periods'], 'integer', 'max' => 10],
            [['id', 'state', 'registry'], 'safe', 'on' => ['enable', 'disable']],
            [['days_before_expire'], 'integer'],
            [['access'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return $this->mergeAttributeLabels([
            'has_contacts' => Yii::t('hipanel.domain.zone', 'Has contacts'),
            'password_required' => Yii::t('hipanel.domain.zone', 'Password required'),
            'registry' => Yii::t('hipanel.domain.zone', 'Registry'),
            'name' => Yii::t('hipanel.domain.zone', 'Name'),
            'state' => Yii::t('hipanel.domain.zone', 'State'),
            'no' => Yii::t('hipanel.domain.zone', 'No.'),
            'add_grace_period' => Yii::t('hipanel.domain.zone', 'Add grace period'),
            'add_grace_limit' => Yii::t('hipanel.domain.zone', 'Add grace limit'),
            'max_delegation' => Yii::t('hipanel.domain.zone', 'Max delegation periods'),
            'autorenew_grace_period' => Yii::t('hipanel.domain.zone', 'Auto-renew grace period'),
            'redemption_grace_period' => Yii::t('hipanel.domain.zone', 'Redemption grace period'),
            'whois_protect_disabled' => Yii::t('hipanel.domain.zone', 'WP disabled'),
            'sync_whois' => Yii::t('hipanel.domain.zone', 'Sync Whois'),
            'wp_disabled' => Yii::t('hipanel.domain.zone', 'WP Prohibited'),
        ]);
    }

    public function getTypeList()
    {
        return Ref::getList('state,zone');
    }

    /**
     * Return name of the zone
     *
     * @return stirng
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Return name of the zone without leading dot
     *
     * @return stirng
     */
    public function getShortName() : string
    {
        return substr($this->name, 1);
    }

    /**
     * Return root of the zone
     *
     * @return stirng
     */
    public function getRootZone() : string
    {
        $parts = explode(".", $this->name);

        return array_pop($parts);
    }

    /**
     * Get hours when procedure delete domain available with refund
     *
     * @return int||null
     */
    public function getAddGracePeriod() : ?int
    {
        return $this->getQuantity('add_grace_period');
    }

    /**
     * Get days when procedure renew are available after domain expire
     *
     * @return int||null
     */
    public function getAutoRenewGracePeriod() : ?int
    {
        return $this->getQuantity('autorenew_grace_period');
    }

    /**
     * Get redemption period for zone
     *
     * @return int||null
     */
    public function getRedemptionPeriod() : ?int
    {
        return $this->getQuantity('redemption_grace_period');
    }

    /**
     * Get max period of domain delegation in zone
     *
     * @return int
     */
    public function getMaxDelegation() : int
    {
        return $this->getQuantity('max_delegation', 10);
    }

    /**
     * Get max periods when domain renew is available
     *
     * @return string||null
     */
    public function getDaysBeforeExpires() : ?int
    {
        return $this->getQuantity('days_before_expire');
    }

    public function isWPProhibited(): bool
    {
        return isset($this->wp_disabled) && ((bool) $this->wp_disabled === true);
    }

    /**
     * Parse attribute value and return quantity
     *
     * @param string $attribute
     * @param int $default = null
     * @return int||null
     */
    protected function getQuantity(string $attribute, int $default = null) : ?int
    {
        if (empty($this->$attribute)) {
            return $default;
        }

        if (strpos($this->$attribute, ' ') === false) {
            return $this->$attribute;
        }

        return array_shift(explode(" ", $this->$attribute, 2));
    }

    /**
     * Parse attribute value and return period name
     *
     * @param string $attribute
     * @return string
     */
    protected function getPeriod(string $attribute) : string
    {
        if (empty($this->$attribute)) {
            return 'day';
        }

        if (strpos($this->$attribute, ' ') === false) {
            return 'day';
        }

        return array_pop(explode(" ", $this->$attribute, 2));
    }
}
