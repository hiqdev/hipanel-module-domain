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

class DomainRegistrationProduct extends AbstractDomainProduct
{
    /** {@inheritdoc} */
    protected $_purchaseModel = DomainRegistrationPurchase::class;

    /** {@inheritdoc} */
    protected $_operation = 'registration';

    /**
     * @var string
     */
    public $registrant;

    /** {@inheritdoc} */
    public function getId()
    {
        return hash('crc32b', implode('_', ['domain', 'registration', $this->name]));
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            ['registrant', 'integer']
        ]);
    }

    /** {@inheritdoc} */
    public function load($data, $formName = null)
    {
        if ($result = parent::load($data, '')) {
            $this->description = Yii::t('hipanel:domain', 'Registration');
        }

        return $result;
    }
}
