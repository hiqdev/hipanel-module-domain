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
use hipanel\modules\domain\models\Zone;
use hipanel\modules\domain\models\ZoneSearch;
use hipanel\modules\finance\cart\AbstractCartPosition;
use hipanel\validators\DomainValidator;
use hiqdev\yii2\cart\DontIncrementQuantityWhenAlreadyInCart;
use Yii;

abstract class AbstractDomainProduct extends AbstractCartPosition implements DontIncrementQuantityWhenAlreadyInCart
{
    /**
     * Default value for max delegation period
     */
    const DEFAULT_QUANTITY_LIMIT = 10;
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
        [, $zone] = explode('.', $this->name, 2);

        return $zone;
    }

    /** {@inheritdoc} */
    public function getQuantityOptions()
    {
        if ($this->_model) {
            $limit = $this->_model->getMaxDelegation() ?? self::DEFAULT_QUANTITY_LIMIT;
        } else {
            $limits = Domain::getZonesLimits();
            $limit = $limits[$this->getZone()]['max_delegation'] ?? self::DEFAULT_QUANTITY_LIMIT;
        }

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

    public function renderDescription(bool $isTopCart = false)
    {
        $description = parent::renderDescription();
        $relatedPositions = [];
        if (($positions = $this->getRelatedPositions()) && !$isTopCart) {
            foreach ($positions as $position) {
                $relatedPositions[] = $position->render();
            }
        }

        return sprintf('%s<br/>%s', $description, implode('<br/>', $relatedPositions));
    }

}
