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

class DomainTransferPurchase extends AbstractPurchase
{
    /**
     * @var string the EPP password for the domain transfer
     */
    public $password;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['password'], 'required'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        if ($this->validate()) {
            static::perform('Transfer', $this->getAttributes());
            return true;
        }

        return false;
    }
}
