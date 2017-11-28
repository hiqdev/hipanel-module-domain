<?php

namespace hipanel\modules\domain\cart;

class PremiumRenewalPurchase extends AbstractPremiumPurchase
{
    /** {@inheritdoc} */
    public static function operation()
    {
        return 'Renew';
    }
}
