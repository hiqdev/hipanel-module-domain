<?php

namespace hipanel\modules\domain\cart;

/**
 * Abstract class AbstractPurchase
 * @package hipanel\modules\domain\cart
 */
abstract class AbstractPurchase extends \hipanel\modules\finance\models\AbstractPurchase
{
    use \hipanel\base\ModelTrait;

    /** @inheritdoc */
    public static function index()
    {
        return 'domains';
    }

    /** @inheritdoc */
    public static function type()
    {
        return 'domain';
    }

    /** @inheritdoc */
    public function init()
    {
        parent::init();

        $this->domain = $this->item;
    }

    public function synchronize()
    {
        $this->period = $this->position->getQuantity();
    }

    /** @inheritdoc */
    public function rules() {
        return array_merge(parent::rules(), [
            [['domain', 'zone'], 'safe'],
            [['period'], 'number'],
        ]);
    }
}