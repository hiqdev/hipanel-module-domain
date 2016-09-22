<?php

/*
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

return [
    'aliases' => [
        '@domain' => '/domain/domain',
        '@host'   => '/domain/host',
    ],
    'modules' => [
        'domain' => [
            'class' => \hipanel\modules\domain\Module::class,
        ],
    ],
    'components' => [
        'i18n' => [
            'translations' => [
                'hipanel/domain' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'forceTranslation' => true,
                    'basePath' => '@hipanel/modules/domain/messages',
                    'fileMap' => [
                        'hipanel/domain' => 'domain.php',
                    ],
                ],
            ],
        ],
        'menuManager' => [
            'items' => [
                'sidebar' => [
                    'add' => [
                        'domain' => [
                            'menu' => \hipanel\modules\domain\menus\SidebarMenu::class,
                            'where' => [
                                'after'  => ['tickets', 'finance', 'clients', 'dashboard', 'header'],
                                'before' => ['servers', 'hosting'],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
