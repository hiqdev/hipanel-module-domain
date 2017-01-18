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

class DomainBulkBasicActionsMenu extends \hiqdev\yii2\menus\Menu
{
    public function items()
    {
        return [
            [
                'label' => Yii::t('hipanel:domain', 'Sync contacts'),
                'url' => '#',
                'linkOptions' => ['data-action' => 'sync'],
                'visible' => Yii::$app->user->can('support'),
            ],
            [
                'label' => Yii::t('hipanel:domain', 'Renew'),
                'url' => '#',
                'linkOptions' => ['data-action' => 'bulk-renewal'],
                'visible' => Yii::$app->user->can('domain.pay'),
            ],
            [
                'label' => Yii::t('hipanel:domain', 'Push domain'),
                'url' => '#bulk-domain-push-modal',
                'linkOptions' => ['data-toggle' => 'modal'],
                'visible' => Yii::$app->user->can('domain.pay'),
            ],
            // Hold
            '<li role="presentation" class="divider"></li>',
            [
                'label' => '<i class="fa fa-toggle-on"></i> ' . Yii::t('hipanel:domain', 'Enable Hold'),
                'url' => '#',
                'linkOptions' => ['data-action' => 'enable-hold'],
                'visible' => Yii::$app->user->can('support'),
            ],
            [
                'label' => '<i class="fa fa-toggle-off"></i> ' . Yii::t('hipanel:domain', 'Disable Hold'),
                'url' => '#',
                'linkOptions' => ['data-action' => 'disable-hold'],
                'visible' => Yii::$app->user->can('support'),
            ],
            // WHOIS protect
            '<li role="presentation" class="divider"></li>',
            [
                'label' => '<i class="fa fa-toggle-on"></i> ' . Yii::t('hipanel:domain', 'Enable WHOIS protect'),
                'url' => '#',
                'linkOptions' => ['data-action' => 'enable-whois-protect'],
            ],
            [
                'label' => '<i class="fa fa-toggle-off"></i> ' . Yii::t('hipanel:domain', 'Disable WHOIS protect'),
                'url' => '#',
                'linkOptions' => ['data-action' => 'enable-whois-protect'],
            ],
            // Lock
            '<li role="presentation" class="divider"></li>',
            [
                'label' => '<i class="fa fa-toggle-on"></i> ' . Yii::t('hipanel:domain', 'Enable Lock'),
                'url' => '#',
                'linkOptions' => ['data-action' => 'enable-lock'],
            ],
            [
                'label' => '<i class="fa fa-toggle-off"></i> ' . Yii::t('hipanel:domain', 'Disable Lock'),
                'url' => '#',
                'linkOptions' => ['data-action' => 'disable-lock'],
            ],
            // Autorenew
            '<li role="presentation" class="divider"></li>',
            [
                'label' => '<i class="fa fa-toggle-on"></i> ' . Yii::t('hipanel:domain', 'Enable autorenew'),
                'url' => '#',
                'linkOptions' => ['data-action' => 'enable-autorenewal'],
            ],
            [
                'label' => '<i class="fa fa-toggle-off"></i> ' . Yii::t('hipanel:domain', 'Disable autorenew'),
                'url' => '#',
                'linkOptions' => ['data-action' => 'disable-autorenewal'],
            ],
        ];
    }
}
