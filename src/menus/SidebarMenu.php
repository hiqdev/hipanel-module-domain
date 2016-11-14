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

class SidebarMenu extends \hiqdev\menumanager\Menu
{
    public function items()
    {
        return [
            'domains' => [
                'label' => Yii::t('hipanel', 'Domains'),
                'url'   => ['/domains/domain/index'],
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
                        'label' => Yii::t('hipanel:domain', 'Buy domain'),
                        'url'   => ['/domain/check/check-domain'],
                        'visible' => Yii::$app->user->can('deposit'),
                    ],
                    'transfer' => [
                        'label' => Yii::t('hipanel:domain', 'Transfer domain'),
                        'url'   => ['/domain/transfer/index'],
                        'visible' => Yii::$app->user->can('deposit'),
                    ],
                    'whois' => [
                        'label' => Yii::t('hipanel:domain', 'WHOIS lookup'),
                        'url'   => ['/domain/whois/index'],
                    ],
                ],
            ],
        ];
    }
}
