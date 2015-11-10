<?php

namespace hipanel\modules\domain\models;

use Yii;
use yii\helpers\ArrayHelper;

class DomainTransferProduct extends DomainProduct
{
    protected $_operation = 'transfer';

    public function init() {
        $this->description = Yii::t('app', 'Transfer');
    }

    public function getId()
    {
        return implode('_', ['domain', 'transfer', $this->name]);
    }

    public function rules() {
        return ArrayHelper::merge(parent::rules(), [
            [['password'], 'string'],
        ]);
    }

    public function attributes() {
        return ArrayHelper::merge(parent::attributes(), [
            'password'
        ]);
    }
}