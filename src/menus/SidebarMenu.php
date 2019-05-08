<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\menus;

use Yii;

class SidebarMenu extends \hiqdev\yii2\menus\Menu
{
    public function items()
    {
        return [
            'domains' => [
                'label' => Yii::t('hipanel', 'Domains'),
                'url'   => ['/domain/domain/index'],
                'icon'  => 'fa-globe',
                'items' => [
                    'domains' => [
                        'label' => Yii::t('hipanel', 'Domains'),
                        'url'   => ['/domain/domain/index'],
                    ],
                    'nameservers' => [
                        'label' => Yii::t('hipanel', 'Name Servers'),
                        'url'   => ['/domain/host/index'],
                    ],
                    'contacts' => [
                        'label' => Yii::t('hipanel', 'Contacts'),
                        'url'   => ['/client/contact/index'],
                    ],
                    'check-domain' => [
                        'label' => Yii::t('hipanel:domain', 'Register domain'),
                        'url'   => ['/domain/check/check-domain'],
                        'visible' => Yii::$app->user->can('domain.pay'),
                        'options' => [
                            'data-ga-check' => true
                        ]
                    ],
                    'transfer' => [
                        'label' => Yii::t('hipanel:domain', 'Transfer domain'),
                        'url'   => ['/domain/transfer/index'],
                        'visible' => Yii::$app->user->can('domain.pay'),
                    ],
                    'whois' => [
                        'label' => Yii::t('hipanel:domain', 'WHOIS lookup'),
                        'url'   => ['/domain/whois/index'],
                    ],
                    'zone' => [
                        'label' => Yii::t('hipanel:domain', 'Zone'),
                        'url'   => ['@zone/index'],
                    ],
                ],
            ],
        ];
    }
}
