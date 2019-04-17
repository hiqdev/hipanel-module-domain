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

class PremiumRenewalPurchase extends AbstractPremiumPurchase
{
    public $type = 'premium_dns_renew';

    /** {@inheritdoc} */
    public static function operation()
    {
        return 'pay-feature';
    }

    /** {@inheritdoc} */
    public function init()
    {
        parent::init();

        $this->amount = $this->position->getQuantity();
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['amount'], 'required'],
        ]);
    }
}
