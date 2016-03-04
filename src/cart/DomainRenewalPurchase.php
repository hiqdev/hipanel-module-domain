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

class DomainRenewalPurchase extends AbstractDomainPurchase
{
    /** {@inheritdoc} */
    public static function operation()
    {
        return 'Renew';
    }

    /**
     * @var string domain expiration datetime
     */
    public $expires;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['expires'], 'required'],
        ]);
    }
}
