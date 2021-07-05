<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

return [
    'aliases' => [
        '@domain' => '/domain/domain',
        '@host'   => '/domain/host',
        '@domain-check' => '/domain/check',
        '@domain-contact' => '/domain/contact',
        '@zone' => '/domain/zone',
        '@secdns' => '/domain/secdns',
    ],
    'modules' => [
        'domain' => [
            'class' => \hipanel\modules\domain\Module::class,
            'payableWhoisProtect' => $params['module.domain.whois_protect.payable'] ?? false,
        ],
        'cart' => [
            'shoppingCartOptions' => [
                'as domainRelatedProducts' => [
                    'class' => \hipanel\modules\domain\cart\DomainRelatedProductsBehavior::class,
                ]
            ]
        ]
    ],
    'components' => [
        'themeManager' => [
            'pathMap' => [
                dirname(__DIR__) . '/src/views' => '$themedViewPaths',
            ],
        ],
        'i18n' => [
            'translations' => [
                'hipanel:domain' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'forceTranslation' => true,
                    'basePath' => dirname(__DIR__) . '/src/messages',
                ],
                'hipanel.domain.premium' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => dirname(__DIR__) . '/src/messages',
                ],
                'hipanel.domain.zone' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => dirname(__DIR__) . '/src/messages',
                ],
            ],
        ],
    ],
    'container' => [
        'definitions' => [
            \hipanel\modules\dashboard\menus\DashboardMenu::class => [
                'add' => [
                    'domain' => [
                        'menu' => [
                            'class' => \hipanel\modules\domain\menus\DashboardItem::class,
                        ],
                        'where' => [
                            'after'  => ['finance', 'clients', 'dashboard', 'header'],
                            'before' => ['certificates', 'servers', 'hosting'],
                        ],
                    ],
                ],
            ],
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
                            'after'  => ['finance', 'clients', 'dashboard', 'header'],
                            'before' => ['certificates', 'servers', 'hosting'],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
