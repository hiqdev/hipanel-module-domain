<?php

namespace hipanel\modules\domain\cart;

use yii\base\InvalidConfigException;

class DomainRenewalPurchase extends AbstractPurchase
{
    /**
     * @var string domain expiration datetime
     */
    public $expires;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['expires'], 'safe']
        ]);
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        if ($this->validate()) {
            static::perform('Renew', $this->getAttributes());
            return true;
        }

        return false;
    }
}
