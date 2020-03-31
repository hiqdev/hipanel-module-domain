<?php

namespace hipanel\modules\domain\cart;

use DateInterval;
use DateTimeImmutable;

trait WithDependetQuantityTrait
{
    public function calculateQuantity()
    {
        return round($this->calculateExpirationQuantity()->days / 365, 2);
    }

    public function calculateExpirationQuantity(): DateInterval
    {
        return $this->_model->getExpires()->diff(new DateTimeImmutable());
    }
}
