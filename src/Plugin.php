<?php
/**
 * @link    http://hiqdev.com/hipanel-module-domain
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\domain;

class Plugin extends \hiqdev\pluginmanager\Plugin
{
    protected $_items = [
        'aliases' => [
            "@domain" => "/domain/domain",
            "@host"   => "/domain/host",
        ],
        'menus' => [
            'hipanel\modules\domain\SidebarMenu',
        ],
        'modules' => [
            'domain' => [
                'class' => 'hipanel\modules\domain\Module',
            ],
        ],
    ];

}
