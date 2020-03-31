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
    public function createRelatedPosition(): CalculableModelInterface
    {
        /** @var CalculableModelInterface|CartPositionInterface $position */
        $position = new WhoisProtectRenewalProduct();
        $rootModel = $this->mainPosition->getModel();
        $position->setQuantity($this->mainPosition->getQuantity());
        $position->parent_id = $this->mainPosition->getId();
        $position->model_id = $rootModel->id;
        $position->name = $rootModel->domain;
        $position->description = Yii::t('hipanel:domain', 'WHOIS protect renewal');

        return $position;
    }

    public function getWidget(): Widget
    {
        return Yii::createObject(
            [
                'class' => WithWhoisProtectRenewalPosition::class,
                'mainPosition' => $this->mainPosition,
                'relatedPosition' => $this->relatedPosition,
                'cart' => $this->cart,
            ]
        );
    }
}
