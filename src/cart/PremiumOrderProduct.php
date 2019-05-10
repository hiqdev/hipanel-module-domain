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

use Yii;

class PremiumOrderProduct extends AbstractPremiumProduct
{
    /** {@inheritdoc} */
    protected $_purchaseModel = PremiumOrderPurchase::class;

    /** {@inheritdoc} */
    protected $_operation = 'premium_dns_purchase';

    /** {@inheritdoc} */
    public function getId()
    {
        return hash('crc32b', implode('_', ['premium', 'dns_purchase', $this->name]));
    }

    public function load($data, $formName = null)
    {
        if ($result = parent::load($data, '')) {
            $this->description = Yii::t('hipanel.domain.premium', 'Purchase of premium package');
        }

        return $result;
    }
}
