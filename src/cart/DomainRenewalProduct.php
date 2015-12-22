<?php

/*
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2015, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\cart;

use hipanel\modules\domain\models\Domain;
use Yii;

class DomainRenewalProduct extends AbstractDomainProduct
{
    protected $_operation = 'renewal';

    public static function primaryKey()
    {
        return ['model_id'];
    }

    public function load($data, $formName = null)
    {
        $result = parent::load($data, '');
        if ($result) {
            $this->_model = Domain::findOne($this->model_id);
            $this->name = $this->_model->domain;
            $this->description = Yii::t('app', 'Renewal');
        }

        return $result;
    }

    public function getId()
    {
        return implode('_', ['domain', 'renewal', $this->_model->id]);
    }
}
