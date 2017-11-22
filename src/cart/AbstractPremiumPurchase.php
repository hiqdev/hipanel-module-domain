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
        $this->period = $this->position->getQuantity();
    }

    /** {@inheritdoc} */
    public static function tableName()
    {
        return 'domain';
    }

    /** {@inheritdoc} */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['domain'], 'safe'],
            [['period'], 'number'],
        ]);
    }
}
