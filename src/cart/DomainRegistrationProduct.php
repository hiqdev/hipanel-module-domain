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

class DomainRegistrationProduct extends AbstractDomainProduct
{
    protected $_operation = 'registration';

    public function getId()
    {
        return implode('_', ['domain', 'registration', $this->name]);
    }

    public function load($data, $formName = null)
    {
        $result = parent::load($data, '');
        if ($result) {
            $this->description = Yii::t('app', 'Registration');
        }

        return $result;
    }
}
