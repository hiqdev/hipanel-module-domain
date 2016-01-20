<?php

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
            [['password'], 'required']
        ]);
    }

    /**
     * @inheritdoc
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
