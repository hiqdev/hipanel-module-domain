<?php

namespace hipanel\modules\domain\cart;

class PremiumRenewalProduct extends AbstractPremiumProduct
{
    /** {@inheritdoc} */
    protected $_purchaseModel = PremiumRenewalPurchase::class;

    /** {@inheritdoc} */
    protected $_operation = 'premium_dns_renew';

    /**
     * The method returns the `crc32b` hash of a distinct condition
     * Example:.
     *
     * ```php
     *    return hash('crc32b', implode('_', ['domain', 'registration', $this->name]));
     * ```
     *
     * @return string
     */
    public function getId()
    {
        return hash('crc32b', implode('_', ['premium', 'dns_renew', $this->name]));
    }
}
