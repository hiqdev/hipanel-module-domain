<?php

namespace hipanel\modules\domain\cart;

use hipanel\modules\domain\models\Domain;
use hipanel\modules\finance\cart\AbstractCartPosition;
use hipanel\modules\finance\cart\NotPurchasablePositionException;
use hipanel\modules\finance\cart\PositionPurchasabilityValidatorInterface;
use hiqdev\hiart\ResponseErrorException;

class DomainContactsCompatibilityValidator implements PositionPurchasabilityValidatorInterface
{
    /**
     * @param \hipanel\modules\finance\cart\AbstractCartPosition[] $positions
     * @return void
     */
    public function validate($positions)
    {
        $positions = array_filter($positions, function ($position) {
            return $position instanceof DomainRegistrationProduct;
        });

        $purchases = array_map(function ($position) {
            /** @var AbstractCartPosition $position */
            return $position->getPurchaseModel();
        }, $positions);

        $this->ensureContactsAreCompatibleForPurchases($purchases);
    }


    private function ensureContactsAreCompatibleForPurchases($purchases)
    {
        $data = array_map(function ($purchase) {
            /** @var AbstractDomainPurchase $purchase */
            return $purchase->getAttributes();
        }, $purchases);

        try {
            $result = Domain::perform('check-contacts-compatible', $data, ['batch' => true]);
        } catch (ResponseErrorException $e) {
            $error = $e->getMessage();

            if (strpos($error, 'contact not filled properly') !== false) {
                if (strpos($error, 'RU zone') !== false) {
                    throw ContactIsIncompatibleException::passportRequired();
                }

                throw ContactIsIncompatibleException::generalDataRequired();
            }

            throw new NotPurchasablePositionException();
        }
    }
}
