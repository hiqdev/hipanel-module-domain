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
    /** @inheritdoc */
    protected $_purchaseModel = 'hipanel\modules\domain\cart\DomainRenewalPurchase';

    /** @inheritdoc */
    protected $_operation = 'renewal';

    /** @inheritdoc */
    public static function primaryKey()
    {
        return ['model_id'];
    }

    /** @inheritdoc */
    public function load($data, $formName = null)
    {
        $result = parent::load($data, '');
        if ($result) {
            $this->loadRelatedData();
        }

        return $result;
    }

    private function loadRelatedData() {
        $this->_model = Domain::findOne($this->model_id);
        $this->name = $this->_model->domain;
        $this->description = Yii::t('hipanel/domain', 'Renewal');
    }

    /** @inheritdoc */
    public function getId()
    {
        return hash('crc32b', implode('_', ['domain', 'renewal', $this->_model->id]));
    }

    /** @inheritdoc */
    public function getCalculationModel($options = [])
    {
        return parent::getCalculationModel(array_merge([
            'id' => $this->model_id
        ], $options));
    }

    public function getPurchaseModel($options = [])
    {
        $this->loadRelatedData(); // To get fresh domain expiration date
        return parent::getPurchaseModel(array_merge(['expires' => $this->_model->expires], $options));
    }
}
