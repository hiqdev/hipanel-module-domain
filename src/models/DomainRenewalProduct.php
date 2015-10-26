<?php

namespace hipanel\modules\domain\models;

use Yii;

class DomainRenewalProduct extends DomainProduct
{
    protected $_operation = 'renewal';

    public function load($data, $formName = null)
    {
        $result = parent::load($data, '');
        if ($result) {
            $this->_model = Domain::findOne($this->model_id);
            $this->name = $this->_model->name;
            $this->description = Yii::t('app', 'Renewal');
        }
        return $result;
    }

    public function getId()
    {
        return implode('_', ['domain', 'renewal', $this->_model->id]);
    }
}