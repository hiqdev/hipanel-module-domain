<?php

namespace hipanel\modules\domain\cart;

use hiqdev\hiart\ErrorResponseException;

class DomainRegistrationPurchase extends AbstractPurchase
{
    /**
     * @inheritdoc
     */
    public function execute()
    {
        if ($this->validate()) {
            static::perform('Register', $this->getAttributes());
            return true;
        }

        return false;
    }
}
