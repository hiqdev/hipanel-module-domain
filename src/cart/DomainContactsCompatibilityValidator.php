<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\cart;

use hipanel\modules\domain\models\Domain;
use hipanel\modules\finance\cart\AbstractCartPosition;
use hipanel\modules\finance\cart\AbstractPurchase;
use hiqdev\yii2\cart\NotPurchasableException;
use hipanel\modules\finance\cart\PositionPurchasabilityValidatorInterface;
use hiqdev\hiart\ResponseErrorException;

class DomainContactsCompatibilityValidator implements PositionPurchasabilityValidatorInterface
{
    /**
     * @param \hipanel\modules\finance\cart\AbstractCartPosition[] $positions
     */
    public function validate($positions)
    {
        $positions = array_filter($positions, function ($position) {
            return ($position instanceof DomainRegistrationProduct) || ($position instanceof DomainTransferProduct);
        });

        $purchases = array_map(function ($position) {
            /** @var AbstractCartPosition $position */
            return $position->getPurchaseModel();
        }, $positions);

        $this->ensureContactsAreCompatibleForPurchases($purchases);
    }

    /**
     * @param AbstractPurchase[] $purchases
     * @throws ContactIsIncompatibleException
     * @throws \hiqdev\yii2\cart\NotPurchasableException
     */
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
            $responseData = $e->getResponse()->getData();

            if (strpos($error, 'contact not filled properly') !== false) {
                foreach ($purchases as $purchase) {
                    $id = $purchase->position->getId();
                    if (isset($responseData[$id]['_error_ops']['for']) && $responseData[$id]['_error_ops']['for'] === 'RU') {
                        throw ContactIsIncompatibleException::passportRequired();
                    }
                }

                throw ContactIsIncompatibleException::generalDataRequired();
            }

            throw new \hiqdev\yii2\cart\NotPurchasableException();
        }
    }
}
