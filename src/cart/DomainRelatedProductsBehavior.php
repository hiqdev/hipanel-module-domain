<?php

namespace hipanel\modules\domain\cart;

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
        if ($rootPosition instanceof DomainRenewalProduct && ($relatedPositions = $rootPosition->getRelatedPositions())) {
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
        if ($rootPosition instanceof DomainRenewalProduct && ($relatedPositions = $rootPosition->getRelatedPositions())) {
            foreach ($relatedPositions as $position) {
                $cart->accumulateEvents(static function () use ($cart, $position, $rootPosition) {
                    $cart->update($position->relatedPosition, $rootPosition->getQuantity());
                });
            }
        }
    }
}
