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

/**
 * @property integer $model_id
 */
class WhoisProtectRenewalProduct extends AbstractPremiumProduct
{
    use WithDependetQuantityTrait;

    /** {@inheritdoc} */
    protected $_purchaseModel = WhoisProtectRenewalPurchase::class;

    /** {@inheritdoc} */
    protected $_operation = 'whois_protect_renew';

    /** {@inheritdoc} */
    public function getId()
    {
        return hash('crc32b', implode('_', ['whois_protect', '_renew_', $this->model_id]));
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
            [['quantity'], 'number'],
        ]);
    }

    public function getQuantityOptions(): string
    {
        return Yii::t('hipanel.domain.premium', '{n, plural, one{# day} other{# days}} – till the domain expiration date {expiration}', [
            'n' => $this->calculateExpirationQuantity()->days,
            'expiration' => Yii::$app->formatter->asDate($this->_model->getExpires()),
        ]);
    }

    /** {@inheritdoc} */
    protected function ensureRelatedData(): void
    {
        $this->_model = Domain::find()->where(['id' => $this->model_id])->withPaidWhoisProtect()->one();
        $this->name = $this->_model->domain;
        $this->_quantity = $this->quantity ?? $this->calculateQuantity();
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
