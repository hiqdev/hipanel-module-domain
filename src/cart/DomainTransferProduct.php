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
    protected $_operation = 'transfer';

    public function init()
    {
        $this->description = Yii::t('app', 'Transfer');
    }

    public function getId()
    {
        return implode('_', ['domain', 'transfer', $this->name]);
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['name', 'password'], 'required'],
        ]);
    }

    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(), [
            'password',
        ]);
    }
}
