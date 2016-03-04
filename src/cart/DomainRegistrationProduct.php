<?php

/*
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\cart;

use hipanel\modules\domain\cart\DomainRegistrationPurchase;
use Yii;

class DomainRegistrationProduct extends AbstractDomainProduct
{
    /** {@inheritdoc} */
    protected $_purchaseModel = DomainRegistrationPurchase::class;

    /** {@inheritdoc} */
    protected $_operation = 'registration';

    /** {@inheritdoc} */
    public function getId()
    {
        return hash('crc32b', implode('_', ['domain', 'registration', $this->name]));
    }

    /** {@inheritdoc} */
    public function load($data, $formName = null)
    {
        $result = parent::load($data, '');
        if ($result) {
            $this->description = Yii::t('hipanel/domain', 'Registration');
        }

        return $result;
    }
}
