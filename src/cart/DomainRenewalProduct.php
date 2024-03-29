<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\cart;

use DateTime;
use hipanel\modules\domain\models\Domain;
use hipanel\modules\finance\cart\BatchPurchasablePositionInterface;
use hipanel\modules\finance\cart\BatchPurchaseStrategy;
use hipanel\validators\DomainValidator;
use Yii;

class DomainRenewalProduct extends AbstractDomainProduct implements BatchPurchasablePositionInterface
{
    /** {@inheritdoc} */
    protected $_purchaseModel = 'hipanel\modules\domain\cart\DomainRenewalPurchase';

    /** {@inheritdoc} */
    protected $_operation = 'renewal';

    /** {@inheritdoc} */
    public static function primaryKey()
    {
        return ['model_id'];
    }

    /** {@inheritdoc} */
    public function load($data, $formName = null)
    {
        if ($result = parent::load($data, '')) {
            $this->ensureRelatedData();
        }

        return $result;
    }

    /** {@inheritdoc} */
    protected function ensureRelatedData(): void
    {
        $this->_model = Domain::find()->where(['id' => $this->model_id])->withPaidWhoisProtect()->one();
        $this->name = $this->_model->domain;
        $this->description = Yii::t('hipanel:domain', 'Renewal');
    }

    /** {@inheritdoc} */
    public function getId()
    {
        return hash('crc32b', implode('_', ['domain', 'renewal', $this->_model->id]));
    }

    /** {@inheritdoc} */
    public function getCalculationModel($options = [])
    {
        return parent::getCalculationModel(array_merge([
            'id' => $this->model_id,
            'client' => $this->getModel()->client,
            'seller' => $this->getModel()->seller,
        ], $options));
    }

    /** {@inheritdoc} */
    public function getPurchaseModel($options = [])
    {
        $this->ensureRelatedData(); // To get fresh domain expiration date

        return parent::getPurchaseModel(array_merge(['expires' => $this->_model->expires], $options));
    }

    /**
     * Checks whether domain reached the limit of days before expiration date and can be renewed.
     *
     * @param $attribute
     * @return bool
     */
    public function daysBeforeExpireValidator($attribute)
    {
        if ($this->_model) {
            $minDays = $this->_model->getDaysBeforeExpires();
        } else {
            $limits = Domain::getZonesLimits();
            $minDays = $limits[$this->getZone()]['before_expire'] ?? null;
        }

        if (!isset($minDays)) {
            return true;
        }
        $interval = (new DateTime())->diff(new DateTime($this->_model->expires));
        $diff = $interval->format('%a') - $minDays;
        if ($diff <= 0) {
            return true;
        }

        $date = Yii::$app->formatter->asDate((new DateTime())->add(new \DateInterval("P{$diff}D")));
        $this->addError('id', Yii::t('hipanel:domain', 'Domains in zone {zone} could be renewed only in last {min, plural, one{# day} other{# days}} before the expiration date. You are able to renew domain {domain} only after {date} (in {days, plural, one{# day} other{# days}})', [
            'zone' => (string)$this->getZone(),
            'min' => (int)$minDays,
            'date' => (string)$date,
            'days' => (int)$diff,
            'domain' => (string)$this->name,
        ]));

        return false;
    }

    /**
     * Checks domains status.
     *
     * @param $attribute
     * @return bool
     */
    public function statusValidator($attribute)
    {
        if (!$this->_model->isRenewable()) {
            $this->addError('id', Yii::t('hipanel:domain', 'Domain status prohibits this operation'));
            return false;
        }

        return true;
    }

    /** {@inheritdoc} */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['model_id'], 'integer'],
            [['id'], 'daysBeforeExpireValidator'],
            [['id'], 'statusValidator'],
        ]);
    }

    public function getRelatedPositions(): array
    {
        if (!Yii::$app->getModule('domain')->payableWhoisProtect || !$this->_model->canPayWhoisProtect()) {
            return [];
        }

        return array_filter([
            $this->_model->isWhoisProtectPaid()
                ? WhoisProtectRenewalRelatedPosition::makeOrReturnNull($this)
                : WhoisProtectOrderRelatedPosition::makeOrReturnNull($this),
        ]);
    }

    public function getBatchPurchaseStrategyClass()
    {
        return BatchPurchaseStrategy::class;
    }
}
