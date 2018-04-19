<?php

namespace hipanel\modules\domain\cart;

use hipanel\modules\finance\cart\AbstractPurchase;

abstract class AbstractPremiumPurchase extends AbstractPurchase
{
    public $domain;

    public $type;

    public $object;

    public $amount;

    /** {@inheritdoc} */
    public static function tableName()
    {
        return 'hdomain';
    }

    /** {@inheritdoc} */
    public function init()
    {
        parent::init();

        $this->domain = $this->position->name;
        $this->amount = $this->position->getQuantity();
        $this->object = 'feature';
    }

    /** {@inheritdoc} */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['domain', 'type', 'object'], 'safe'],
            [['amount'], 'number'],
        ]);
    }
}
