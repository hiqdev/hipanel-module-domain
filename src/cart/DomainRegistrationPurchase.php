<?php

namespace hipanel\modules\domain\cart;

class DomainRegistrationPurchase extends AbstractPurchase
{
    /**
     * Executes the purchase.
     * Calls proper API commands to purchase the product.
     * @return boolean whether the item was purchased
     */
    public function execute()
    {
        static::perform('Register', $this->getAttributes());

        return true;
    }
}
