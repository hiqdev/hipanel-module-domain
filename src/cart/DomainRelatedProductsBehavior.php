<?php

namespace hipanel\modules\domain\cart;

use hipanel\modules\domain\models\Domain;
use hiqdev\yii2\cart\CartPositionInterface;
use hiqdev\yii2\cart\ShoppingCart;
use yii\base\Behavior;
use yz\shoppingcart\CartActionEvent;

class DomainRelatedProductsBehavior extends Behavior
{
    public function events(): array
    {
        return [
            ShoppingCart::EVENT_POSITION_PUT => 'handlePositionPut',
            ShoppingCart::EVENT_POSITION_UPDATE => 'handlePositionUpdate',
        ];
    }

    public function handlePositionPut(CartActionEvent $event): void
    {
        /** @var ShoppingCart $cart */
        $cart = $event->sender;
        /** @var CartPositionInterface $rootPosition */
        $rootPosition = $event->position;
        if ($rootPosition instanceof AbstractDomainProduct && ($relatedPositions = $rootPosition->getRelatedPositions())) {
            $positions = [];
            foreach ($relatedPositions as $position) {
                $positions[] = $position->relatedPosition;
            }
            $cart->putPositions($positions);
        }
    }

    public function handlePositionUpdate(CartActionEvent $event): void
    {
        /** @var ShoppingCart $cart */
        $cart = $event->sender;
        /** @var CartPositionInterface $mainPosition */
        $rootPosition = $event->position;
        if ($rootPosition instanceof AbstractDomainProduct && ($relatedPositions = $rootPosition->getRelatedPositions())) {
            $cart->accumulateEvents(
                static function () use ($cart, $relatedPositions, $rootPosition) {
                    foreach ($relatedPositions as $position) {
                        /** @var Domain $relatedModel */
                        $qty = $rootPosition->getQuantity();
                        if ($rootPosition instanceof DomainRegistrationProduct || $rootPosition instanceof DomainTransferProduct) {
                            $position->relatedPosition->setModel($position->relatedPosition->fakeModel($rootPosition->name, $qty));
                        } else if (($relatedModel = $position->relatedPosition->getModel()) && !$relatedModel->isWhoisProtectPaid()) {
                            $qty += $position->relatedPosition->calculateQuantity();
                        }
                        $cart->update($position->relatedPosition, $qty);
                    }
                }
            );
        }
    }
}
