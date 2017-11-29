<?php

namespace hipanel\modules\domain\cart;

use hipanel\modules\finance\cart\AbstractPurchase;

abstract class AbstractPremiumPurchase extends AbstractPurchase
{
    /** {@inheritdoc} */
    public function init()
    {
        parent::init();

        $this->domain = $this->position->name;
        $this->amout = $this->position->getQuantity();
        $this->type = 'premium_dns_renew';
        $this->object = 'feature';
        $this->expries = '';

    }

    /** {@inheritdoc} */
    public static function tableName()
    {
        return 'hdomain';
    }

    /** {@inheritdoc} */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['domain'], 'safe'],
            [['amount'], 'number'],
        ]);
    }
}
