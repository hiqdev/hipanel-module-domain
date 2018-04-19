<?php

namespace hipanel\modules\domain\cart;

use hiqdev\yii2\cart\ShoppingCart;

/**
 * Class RegistrantModifier
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class RegistrantModifier
{
    /**
     * @var ShoppingCart
     */
    private $cart;

    public function __construct(ShoppingCart $cart)
    {
        $this->cart = $cart;
    }

    public function setRegistrantId($id)
    {
        foreach ($this->cart->getPositions() as $position) {
            if (!$position instanceof DomainRegistrationProduct && !$position instanceof DomainTransferProduct) {
                continue;
            }

            $position->registrant = $id;
        }

        $this->cart->saveToSession();
    }
}
