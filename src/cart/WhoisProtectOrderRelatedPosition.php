<?php

namespace hipanel\modules\domain\cart;

use hipanel\modules\finance\cart\RelatedPosition;
use hipanel\modules\finance\models\CalculableModelInterface;
use hipanel\modules\domain\widgets\WithWhoisProtectPosition;
use Yii;
use yii\base\Widget;

class WhoisProtectOrderRelatedPosition extends RelatedPosition
{
    public function createRelatedPosition(): CalculableModelInterface
    {
        $position = new WhoisProtectOrderProduct();
        $rootPositionId = $this->mainPosition->getId();
        $rootModel = $this->mainPosition->getModel();
        $qty = $this->mainPosition->getQuantity();
        if ($rootModel) {
            $position->setModel($rootModel);
            $position->load(['name' => $rootModel->domain, 'parent_id' => $rootPositionId]);
            $qty += $position->calculateQuantity();
        } else {
            $domainName = $this->mainPosition->name;
            $position->setModel($position->fakeModel($domainName, $qty));
            $position->load(['parent_id' => $rootPositionId]);
        }
        $position->setQuantity($qty);

        return $position;
    }

    public function getWidget(): Widget
    {
        return Yii::createObject(
            [
                'class' => WithWhoisProtectPosition::class,
                'relatedPosition' => $this->relatedPosition,
                'mainPosition' => $this->mainPosition,
                'cart' => $this->cart,
            ]
        );
    }
}
