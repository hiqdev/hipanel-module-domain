<?php

/*
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\menus;

use Yii;

class CheckDomainMenu extends \hiqdev\menumanager\Menu
{
    public function items()
    {
        return [
            'check-domain' => [
                'label' => $this->render('checkDomain'),
                'encode' => false,
            ],
        ];
    }
}
