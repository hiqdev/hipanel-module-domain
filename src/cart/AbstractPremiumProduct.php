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
use hipanel\modules\finance\cart\AbstractCartPosition;
use hiqdev\yii2\cart\DontIncrementQuantityWhenAlreadyInCart;
use Yii;

/**
 * Class AbstractPremiumProduct
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 *
 * @property string $name The domain name
 */
abstract class AbstractPremiumProduct extends AbstractCartPosition implements DontIncrementQuantityWhenAlreadyInCart
{
    /**
     * @var Domain
     */
    protected $_model;

    /**
     * @var string the operation name
     */
    protected $_operation;

    /** {@inheritdoc} */
    protected $_calculationModel = PremiumCalculation::class;

    /** {@inheritdoc} */
    public function getIcon()
    {
        return '<i class="fa fa-globe"></i>';
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['name'], 'safe'],
        ]);
    }

    public function load($data, $formName = null)
    {
        if ($result = parent::load($data, '')) {
            $this->ensureRelatedData();
        }

        return $result;
    }

    /** {@inheritdoc} */
    public function getQuantityOptions()
    {
        $result = [];

        for ($n = 1; $n <= 10; ++$n) {
            $result[$n] = Yii::t('hipanel:domain', '{0, plural, one{# year} other{# years}}', $n);
        }

        return $result;
    }

    /** {@inheritdoc} */
    public function getCalculationModel($options = [])
    {
        $localOptions = [
            'type' => $this->_operation,
            'domain' => $this->name,
        ];

        return parent::getCalculationModel(array_merge($localOptions, $options));
    }

    /**
     * @return string
     */
    public function getOperation(): string
    {
        return $this->_operation;
    }

    protected function serializationMap()
    {
        $parent = parent::serializationMap();
        $parent['_model'] = $this->_model;
        $parent['_operation'] = $this->_operation;

        return $parent;
    }

    protected function ensureRelatedData(): void
    {
    }
}
