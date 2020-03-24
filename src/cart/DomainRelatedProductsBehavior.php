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
            ShoppingCart::EVENT_POSITION_PUT => 'handle',
        ];
    }

    public function handle(CartActionEvent $event): void
    {
        /** @var ShoppingCart $cart */
        $cart = $event->sender;
        /** @var CartPositionInterface $mainPosition */
        $mainPosition = $event->position;
        if ($mainPosition instanceof DomainRenewalProduct && ($relatedPositions = $mainPosition->getRelatedPositions())) {
            foreach ($relatedPositions as $position) {
                $cart->put($position->relatedPosition);
            }
        }
    }
}
