<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\cart;

use hipanel\modules\domain\models\Domain;
use Yii;

class WhoisProtectRenewalProduct extends AbstractPremiumProduct
{
    /** {@inheritdoc} */
    protected $_purchaseModel = WhoisProtectRenewalPurchase::class;

    /** {@inheritdoc} */
    protected $_operation = 'whois_protect_renew';

    /** {@inheritdoc} */
    public function getId()
    {
        return hash('crc32b', implode('_', ['whois_protect', '_renew', $this->name]));
    }

    /** {@inheritdoc} */
    public static function primaryKey()
    {
        return ['model_id'];
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['model_id'], 'integer'],
        ]);
    }

    /** {@inheritdoc} */
    protected function ensureRelatedData(): void
    {
        $this->_model = Domain::findOne($this->model_id);
        $this->name = $this->_model->domain;
        $this->description = Yii::t('hipanel:domain', 'WHOIS protect renewal');
    }

    /** {@inheritdoc} */
    public function getCalculationModel($options = [])
    {
        return parent::getCalculationModel(array_merge([
            'object' => 'feature',
            'domain' => $this->name,
        ], $options));
    }
}
