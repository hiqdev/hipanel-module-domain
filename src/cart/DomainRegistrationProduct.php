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

use hipanel\modules\domain\models\Domain;
use hipanel\modules\finance\cart\BatchPurchasablePositionInterface;
use hipanel\modules\finance\cart\BatchPurchaseStrategy;
use Yii;

class DomainRegistrationProduct extends AbstractDomainProduct implements BatchPurchasablePositionInterface
{
    /** {@inheritdoc} */
    protected $_purchaseModel = DomainRegistrationPurchase::class;

    /** {@inheritdoc} */
    protected $_operation = 'registration';

    /**
     * @var string
     */
    public $registrant;

    /** {@inheritdoc} */
    public function getId()
    {
        return hash('crc32b', implode('_', ['domain', 'registration', $this->name]));
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            ['registrant', 'integer'],
        ]);
    }

    /** {@inheritdoc} */
    public function load($data, $formName = null)
    {
        if ($result = parent::load($data, '')) {
            $this->description = Yii::t('hipanel:domain', 'Registration');
        }

        return $result;
    }

    public function getBatchPurchaseStrategyClass()
    {
        return BatchPurchaseStrategy::class;
    }

    protected function serializationMap()
    {
        $parent = parent::serializationMap();
        $parent['registrant'] = $this->registrant;

        return $parent;
    }

    public function getRelatedPositions(): array
    {
        if (Yii::$app->getModule('domain')->payableWhoisProtect && (new Domain(['domain' => $this->name]))->canPayWhoisProtect()) {
            return array_filter([
                WhoisProtectOrderRelatedPosition::makeOrReturnNull($this)
            ]);
        }

        return [];
    }
}
