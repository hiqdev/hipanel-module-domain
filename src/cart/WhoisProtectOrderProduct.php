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

use DateInterval;
use DateTimeImmutable;
use hipanel\modules\domain\models\Domain;
use Yii;

/**
 * Class WhoisProtectOrderProduct
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class WhoisProtectOrderProduct extends AbstractPremiumProduct
{
    /** {@inheritdoc} */
    protected $_purchaseModel = WhoisProtectOrderPurchase::class;

    /** {@inheritdoc} */
    protected $_operation = 'whois_protect_purchase';

    /** {@inheritdoc} */
    public function getId()
    {
        return hash('crc32b', implode('_', ['whois_protect', 'purchase', $this->name]));
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
        $this->_model = Domain::findOne(['domains' => $this->name]);
        $this->_quantity = round($this->calculateExpirationQuantity()->days / 365, 2);
        $this->description = Yii::t('hipanel.domain.premium', 'Purchase of WHOIS privacy');
    }

    private function calculateExpirationQuantity(): DateInterval
    {
        return $this->_model->getExpires()->diff(new DateTimeImmutable());
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
