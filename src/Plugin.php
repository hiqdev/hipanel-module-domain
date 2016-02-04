<?php

/*
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain;

class Plugin extends \hiqdev\pluginmanager\Plugin
{
    protected $_items = [
        'aliases' => [
            '@domain' => '/domain/domain',
            '@host'   => '/domain/host',
        ],
        'menus' => [
            'hipanel\modules\domain\SidebarMenu',
        ],
        'modules' => [
            'domain' => [
                'class' => 'hipanel\modules\domain\Module',
            ],
        ],
        'components' => [
            'i18n' => [
                'translations' => [
                    'hipanel/domain' => [
                        'class' => 'yii\i18n\PhpMessageSource',
                        'basePath' => '@hipanel/modules/domain/messages',
                        'fileMap' => [
                            'hipanel/domain' => 'domain.php',
                        ],
                    ],
                ],
            ],
        ],
    ];
}
