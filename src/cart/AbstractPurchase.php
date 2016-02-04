<?php

/*
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\cart;

/**
 * Abstract class AbstractPurchase.
 */
abstract class AbstractPurchase extends \hipanel\modules\finance\models\AbstractPurchase
{
    use \hipanel\base\ModelTrait;

    /**
     * @var AbstractDomainProduct|DomainRegistrationProduct
     */
    public $position;

    /** {@inheritdoc} */
    public static function index()
    {
        return static::type() . 's';
    }

    /** {@inheritdoc} */
    public static function type()
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

    /** {@inheritdoc} */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['domain', 'zone'], 'safe'],
            [['period'], 'number'],
        ]);
    }
}
