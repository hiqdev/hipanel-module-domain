<?php

namespace hipanel\modules\domain\models;

use Yii;

class DomainRegistrationProduct extends DomainProduct
{
    protected $_operation = 'transfer';

    public function getId()
    {
        return implode('_', ['domain', 'transfer', $this->name]);
    }

    public function load($data, $formName = null)
    {
        $result = parent::load($data, '');
        if ($result) {
            $this->description = Yii::t('app', 'Transfer');
        }
        return $result;
    }
}