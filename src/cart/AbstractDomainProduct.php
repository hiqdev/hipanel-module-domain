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
use hipanel\validators\DomainValidator;
use hiqdev\yii2\cart\DontIncrementQuantityWhenAlreadyInCart;
use Yii;

abstract class AbstractDomainProduct extends AbstractCartPosition implements DontIncrementQuantityWhenAlreadyInCart
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
    protected $_calculationModel = Calculation::class;

    /**
     * @var integer[] The limit of quantity (years of purchase/renew) for each domain zone in years
     */
    protected $quantityLimits = [
        'ru' => 1,
        'su' => 1,
        'рф' => 1,
        'xn--p1ai' => 1,
        '*' => 10,
    ];

    /** {@inheritdoc} */
    public function getIcon()
    {
        return '<i class="fa fa-globe"></i>';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
        ];
    }

    public function getName(): string
    {
        return DomainValidator::convertAsciiToIdn($this->name);
    }

    /**
     * Extracts domain zone from.
     * @return string
     */
    public function getZone()
    {
        list(, $zone) = explode('.', $this->name, 2);

        return $zone;
    }

    /** {@inheritdoc} */
    public function getQuantityOptions()
    {
        $result = [];
        $limit = isset($this->quantityLimits[$this->getZone()]) ? $this->quantityLimits[$this->getZone()] : $this->quantityLimits['*'];

        if ($this->_model) {
            $interval = (new \DateTime())->diff(new \DateTime($this->_model->expires));
            if ($interval->y >= 0 && !$interval->invert) {
                $limit -= $interval->y;
                if ($interval->m > 0 || $interval->d > 0) {
                    --$limit;
                }
            }
        }

        $limit = $limit < 1 ? 1 : $limit;
        for ($n = 1; $n <= $limit; ++$n) {
            $result[$n] = Yii::t('hipanel:domain', '{0, plural, one{# year} other{# years}}', $n);
        }

        return $result;
    }

    /** {@inheritdoc} */
    public function getCalculationModel($options = [])
    {
        return parent::getCalculationModel(array_merge([
            'type' => $this->_operation,
            'domain' => $this->name,
            'zone' => $this->getZone(),
        ], $options));
    }

    protected function serializationMap()
    {
        $parent = parent::serializationMap();
        $parent['_operation'] = $this->_operation;
        $parent['_model'] = $this->_model;

        return $parent;
    }
}
