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

use Yii;
use yii\helpers\ArrayHelper;

class DomainTransferProduct extends AbstractDomainProduct
{
    /** @inheritdoc */
    protected $_operation = 'transfer';

    /** @inheritdoc */
    public function init()
    {
        $this->description = Yii::t('app', 'Transfer');
    }

    /** @inheritdoc */
    public function getId()
    {
        return hash('crc32b', implode('_', ['domain', 'transfer', $this->name]));
    }

    /** @inheritdoc */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['name', 'password'], 'required'],
        ]);
    }

    /** @inheritdoc */
    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(), [
            'password',
        ]);
    }
}
