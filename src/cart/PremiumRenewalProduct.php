<?php

namespace hipanel\modules\domain\cart;

use hipanel\modules\domain\models\Domain;
use Yii;

class PremiumRenewalProduct extends AbstractPremiumProduct
{
    /** {@inheritdoc} */
    protected $_purchaseModel = PremiumRenewalPurchase::class;

    /** {@inheritdoc} */
    protected $_operation = 'premium_dns_renew';

    /** {@inheritdoc} */
    public function getId()
    {
        return hash('crc32b', implode('_', ['premium', 'dns_renew', $this->name]));
    }

    /** {@inheritdoc} */
    public function load($data, $formName = null)
    {
        if ($result = parent::load($data, '')) {
            $this->ensureRelatedData();
        }

        return $result;
    }

    /** {@inheritdoc} */
    private function ensureRelatedData()
    {
        $this->_model = Domain::findOne(['id' => $this->model_id]);
        $this->name = $this->_model->domain;
        $this->description = Yii::t('hipanel:domain', 'Renewal asdf');
    }
}
