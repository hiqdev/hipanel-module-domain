<?php

namespace hipanel\modules\domain\cart;

use hiqdev\yii2\cart\CartPositionInterface;
use hiqdev\yii2\cart\RelatedPosition;
use hipanel\modules\domain\widgets\WithWhoisProtectPosition;
use Yii;
use yii\base\Widget;

class WhoisProtectOrderRelatedPosition extends RelatedPosition
{
    /** @var Widget */
    private $widget;

    public function createRelatedPosition(): CartPositionInterface
    {
        $position = new WhoisProtectOrderProduct(['name' => $this->mainPosition->name]);
        $position->setQuantity($this->mainPosition->getQuantity());

        return $this->calculate($position);
    }

    public function getWidget(): Widget
    {
        if (empty($this->widget)) {
            $this->widget = Yii::createObject([
                'class' => WithWhoisProtectPosition::class,
                'relatedPosition' => $this->createRelatedPosition(),
                'mainPosition' => $this->mainPosition,
                'cart' => $this->cart,
            ]);
        }

        return $this->widget;
    }
}
