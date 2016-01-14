<?php

/*
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2015, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\cart;

use hipanel\modules\finance\cart\AbstractCartPosition;
use Yii;

abstract class AbstractDomainProduct extends AbstractCartPosition
{
    /**
     * @var string the operation name
     */
    protected $_operation;

    /** @inheritdoc */
    protected $_calculationModel = 'hipanel\modules\domain\cart\Calculation';

    /** @inheritdoc */
    protected $_purchaseModel = 'hipanel\modules\domain\cart\Purchase';

    /**
     * @var integer[] The limit of quantity (years of purchase/renew) for each domain zone in years
     */
    protected $quantityLimits = [
        'ru' => 1,
        'su' => 1,
        '*' => 10,
    ];

    /** @inheritdoc */
    public function getIcon()
    {
        return '<i class="fa fa-globe"></i>';
    }

    /** @inheritdoc */
    public function getZone()
    {
        list (, $zone) = explode('.', $this->name, 2);
        return $zone;
    }

    /** @inheritdoc */
    public function getQuantityOptions()
    {
        $result = [];
        $limit = isset($this->quantityLimits[$this->getZone()]) ? $this->quantityLimits[$this->getZone()] : $this->quantityLimits['*'];
        for ($n = 1; $n <= $limit; $n++) {
            $result[$n] = Yii::t('app', '{0, plural, one{# year} other{# years}}', $n);
        }

        return $result;
    }
}
