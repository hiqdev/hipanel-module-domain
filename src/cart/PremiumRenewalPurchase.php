<?php

namespace hipanel\modules\domain\cart;

class PremiumRenewalPurchase extends AbstractPremiumPurchase
{
    /**
     * @var string premium expiration datetime
     */
    public $expires;

    /** {@inheritdoc} */
    public static function operation()
    {
        return 'pay-feature';
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['expires'], 'required'],
        ]);
    }
}
