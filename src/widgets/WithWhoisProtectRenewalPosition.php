<?php

namespace hipanel\modules\domain\widgets;

use hipanel\helpers\Url;

class WithWhoisProtectRenewalPosition extends WithWhoisProtectPosition
{
    public function getToCartUrl(): string
    {
        return Url::toRoute([
            '@domain/add-to-cart-whois-protect-renewal',
            'model_id' => $this->mainPosition->model_id,
            'parent_id' => $this->mainPosition->getId(),
            'quantity' => $this->mainPosition->getQuantity(),
        ]);
    }
}
