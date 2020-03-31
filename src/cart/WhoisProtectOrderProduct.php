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
 * Class WhoisProtectOrderProduct
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class WhoisProtectOrderProduct extends AbstractPremiumProduct
{
    use WithDependetQuantityTrait;

    /** {@inheritdoc} */
    protected $_purchaseModel = WhoisProtectOrderPurchase::class;

    /** {@inheritdoc} */
    protected $_operation = 'whois_protect_purchase';

    /** {@inheritdoc} */
    public function getId()
    {
        return hash('crc32b', implode('_', ['whois_protect', 'purchase', $this->name]));
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
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
        if (!$this->_model) {
            $this->_model = Domain::findOne(['domains' => $this->name]) ?? new Domain(['domain' => $this->name, 'expires' => (new \DateTime())->modify('+1 year')->format('c')]);
        }
        $this->name = $this->_model->domain;
        $this->_quantity = $this->quantity ?? $this->calculateQuantity();
        $this->description = Yii::t('hipanel.domain.premium', 'Purchase of WHOIS privacy');
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
