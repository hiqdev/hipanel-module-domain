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
use yii\helpers\ArrayHelper;

class DomainTransferProduct extends AbstractDomainProduct
{
    /** {@inheritdoc} */
    protected $_operation = 'transfer';

    /** {@inheritdoc} */
    protected $_purchaseModel = DomainTransferPurchase::class;

    /**
     * @var string
     */
    public $registrant;

    /** {@inheritdoc} */
    public function init()
    {
        $this->description = Yii::t('hipanel:domain', 'Transfer');
    }

    /** {@inheritdoc} */
    public function getId()
    {
        return hash('crc32b', implode('_', ['domain', 'transfer', $this->name]));
    }

    /** {@inheritdoc} */
    public function getQuantityOptions()
    {
        // TODO: Get zones without renewal after transer from DB
        $amount = in_array($this->getZone(), ['ru', 'su', 'рф', 'xn--p1ai'], true) ? 0 : 1;

        return [$amount => Yii::t('hipanel:domain', '{0, plural, one{# year} other{# years}}', $amount)];
    }

    /** {@inheritdoc} */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['password'], 'required'],
            ['registrant', 'integer'],
        ]);
    }

    /** {@inheritdoc} */
    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(), [
            'password',
        ]);
    }

    /** {@inheritdoc} */
    public function getPurchaseModel($options = [])
    {
        return parent::getPurchaseModel(array_merge(['password' => $this->password], $options));
    }

    protected function serializationMap()
    {
        $parent = parent::serializationMap();
        $parent['registrant'] = $this->registrant;

        return $parent;
    }

    public function getRelatedPositions(): array
    {
        if (Yii::$app->getModule('domain')->payableWhoisProtect && (new Domain(['domain' => $this->name]))->canPayWhoisProtect()) {
            return [
                WhoisProtectOrderRelatedPosition::makeOrReturnNull($this),
            ];
        }

        return [];
    }
}
