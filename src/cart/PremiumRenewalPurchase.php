<?php

namespace hipanel\modules\domain\cart;

class PremiumRenewalPurchase extends AbstractPremiumPurchase
{
    public $type = 'premium_dns_renew';

    /** {@inheritdoc} */
    public static function operation()
    {
        return 'pay-feature';
    }

    /** {@inheritdoc} */
    public function init()
    {
        parent::init();

        $this->amount = $this->position->getQuantity();
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['amount'], 'required'],
        ]);
    }
}
