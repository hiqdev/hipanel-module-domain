<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\cart;

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
        return [1 => Yii::t('hipanel:domain', '{0, plural, one{# year} other{# years}}', 1)];
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
}
