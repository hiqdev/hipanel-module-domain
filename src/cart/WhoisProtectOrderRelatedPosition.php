<?php

namespace hipanel\modules\domain\cart;

use hipanel\modules\finance\logic\Calculator;
use hiqdev\yii2\cart\CartPositionInterface;
use hiqdev\yii2\cart\RelatedPosition;
use hiqdev\yii2\cart\RelatedPositionInterface;
use Yii;

class WhoisProtectOrderRelatedPosition extends RelatedPosition
{
    public function createRelatedPosition(): CartPositionInterface
    {
        $position = new WhoisProtectOrderProduct(['name' => $this->mainPosition->name]);
        $position->setQuantity($this->mainPosition->getQuantity());

        return $this->calculate($position);
    }
}
