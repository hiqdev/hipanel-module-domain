<?php
/**
 * @link    http://hiqdev.com/hipanel-module-domain
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\domain;

class Menu extends \hipanel\base\Menu implements \yii\base\BootstrapInterface
{

    protected $_menus = [
        'sidebar' => [
            'where' => [
                'after'     => ['tickets', 'finance', 'clients', 'dashboard', 'header'],
                'before'    => ['servers', 'hosting'],
            ],
            'items' => [
                'domains' => [
                    'label' => 'Domains',
                    'url'   => ['/domains/default/index'],
                    'icon'  => 'fa-globe',
                    'items' => [
                        'domains' => [
                            'label' => 'My domains',
                            'url'   => ['/domain/domain/index'],
                            'icon'  => 'fa-globe',
                        ],
                        'nameservers' => [
                            'label' => 'Name Servers',
                            'url'   => ['/domain/host/index'],
                            'icon'  => 'fa-circle-o',
                        ],
                        'contacts' => [
                            'label' => 'Contacts',
                            'url'   => ['/client/contact/index'],
                            'icon'  => 'fa-circle-o',
                        ],
                        'seo' => [
                            'label' => 'SEO',
                            'url'   => ['/domain/domain/index'],
                            'icon'  => 'fa-circle-o',
                        ],
                    ],
                ],
            ],
        ],
    ];

}
