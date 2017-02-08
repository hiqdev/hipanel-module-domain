<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

return [
    'aliases' => [
        '@domain' => '/domain/domain',
        '@host'   => '/domain/host',
        '@domain-check' => '/domain/check',
    ],
    'modules' => [
        'domain' => [
            'class' => \hipanel\modules\domain\Module::class,
        ],
    ],
    'components' => [
        'themeManager' => [
            'pathMap' => [
                '@hipanel/modules/domain/views' => '$themedViewPaths',
            ],
        ],
        'i18n' => [
            'translations' => [
                'hipanel:domain' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'forceTranslation' => true,
                    'basePath' => '@hipanel/modules/domain/messages',
                ],
            ],
        ],
    ],
    'container' => [
        'definitions' => [
            \hiqdev\thememanager\menus\AbstractSidebarMenu::class => [
                'add' => [
                    'check-domain' => [
                        'menu' => \hipanel\modules\domain\menus\CheckDomainMenu::class,
                        'where' => 'first',
                    ],
                    'domain' => [
                        'menu' => [
                            'class' => \hipanel\modules\domain\menus\SidebarMenu::class,
                        ],
                        'where' => [
                            'after'  => ['tickets', 'finance', 'clients', 'dashboard', 'header'],
                            'before' => ['servers', 'hosting'],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
