<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\cart;

/**
 * Abstract class AbstractDomainPurchase.
 * Holds data to perform domain purchase:
 * - domain
 * - zone
 * - period - how many years
 */
abstract class AbstractDomainPurchase extends \hipanel\modules\finance\cart\AbstractPurchase
{
    use \hipanel\base\ModelTrait;

    protected ruZones = [
        'ru' => 1,
        'su' => 1,
        'рф' => 1,
        'xn--p1ai' => 1,
    ];

    /** {@inheritdoc} */
    public static function tableName()
    {
        return 'domain';
    }

    /** {@inheritdoc} */
    public function init()
    {
        parent::init();
        $this->domain = $this->position->name;
        $this->period = $this->position->getQuantity();
        $this->zone = $this->position->getZone();
    }

    public function isRuZone()
    {
        return isset($this->ruZones[$this->zone]);
    }

    /** {@inheritdoc} */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['domain', 'zone'], 'safe'],
            [['period'], 'number'],
        ]);
    }
}
