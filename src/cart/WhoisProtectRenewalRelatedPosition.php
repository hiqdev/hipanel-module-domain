<?php

namespace hipanel\modules\domain\cart;

use hipanel\modules\finance\cart\RelatedPosition;
use hipanel\modules\finance\models\CalculableModelInterface;
use hipanel\modules\domain\widgets\WithWhoisProtectRenewalPosition;
use hiqdev\yii2\cart\CartPositionInterface;
use Yii;
use yii\base\Widget;

class WhoisProtectRenewalRelatedPosition extends RelatedPosition
{
    /** @var Widget */
    private $widget;

    public function createRelatedPosition(): CalculableModelInterface
    {
        /** @var CalculableModelInterface|CartPositionInterface $position */
        $position = new WhoisProtectRenewalProduct();
        $position->load(['model_id' => $this->mainPosition->model_id]);
        $position->setQuantity($this->mainPosition->getQuantity());
        $position->parent_id = $this->mainPosition->getId();
        if (!Yii::$app->request->isAjax && $position->getModel()->isWhoisProtectPaid()) {
            $this->cart->putPositions([$position]);
        }

        return $this->calculate($position);
    }

    public function getWidget(): Widget
    {
        if (empty($this->widget)) {
            $this->widget = Yii::createObject([
                'class' => WithWhoisProtectRenewalPosition::class,
                'relatedPosition' => $this->createRelatedPosition(),
                'mainPosition' => $this->mainPosition,
                'cart' => $this->cart,
            ]);
        }

        return $this->widget;
    }
}
