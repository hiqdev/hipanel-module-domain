<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\menus;

use Yii;
use yii\helpers\Url;

class DomainBulkBasicActionsMenu extends \hiqdev\yii2\menus\Menu
{
    public function items()
    {
        return [
            [
                'label' => Yii::t('hipanel:domain', 'Sync contacts'),
                'url' => '#',
                'linkOptions' => ['data-action' => Url::to(['@domain/sync'])],
                'visible' => Yii::$app->user->can('support'),
            ],
            '<li role="presentation" class="divider"></li>',
            [
                'label' => Yii::t('hipanel:domain', 'Renew'),
                'url' => '#',
                'linkOptions' => ['data-action' => Url::to(['@domain/bulk-renewal'])],
                'visible' => Yii::$app->user->can('domain.pay'),
            ],
            [
                'label' => Yii::t('hipanel:domain', 'Push domain'),
                'url' => '#bulk-domain-push-modal',
                'linkOptions' => ['data-toggle' => 'modal'],
                'visible' => Yii::$app->user->can('domain.pay') || Yii::$app->user->can('manage'),
            ],
            // Hold
            '<li role="presentation" class="divider"></li>',
            [
                'label' => '<i class="fa fa-toggle-on"></i> ' . Yii::t('hipanel:domain', 'Enable Hold'),
                'url' => '#',
                'linkOptions' => ['data-action' => Url::to(['@domain/enable-hold'])],
                'visible' => Yii::$app->user->can('support'),
            ],
            [
                'label' => '<i class="fa fa-toggle-off"></i> ' . Yii::t('hipanel:domain', 'Disable Hold'),
                'url' => '#',
                'linkOptions' => ['data-action' => Url::to(['@domain/disable-hold'])],
                'visible' => Yii::$app->user->can('support'),
            ],
            // WHOIS protect
            '<li role="presentation" class="divider"></li>',
            [
                'label' => '<i class="fa fa-toggle-on"></i> ' . Yii::t('hipanel:domain', 'Enable WHOIS protect'),
                'url' => '#',
                'linkOptions' => ['data-action' => Url::to(['@domain/enable-whois-protect'])],
            ],
            [
                'label' => '<i class="fa fa-toggle-off"></i> ' . Yii::t('hipanel:domain', 'Disable WHOIS protect'),
                'url' => '#',
                'linkOptions' => ['data-action' => Url::to(['@domain/disable-whois-protect'])],
            ],
            // Lock
            '<li role="presentation" class="divider"></li>',
            [
                'label' => '<i class="fa fa-toggle-on"></i> ' . Yii::t('hipanel:domain', 'Enable Lock'),
                'url' => '#',
                'linkOptions' => ['data-action' => Url::to(['@domain/enable-lock'])],
            ],
            [
                'label' => '<i class="fa fa-toggle-off"></i> ' . Yii::t('hipanel:domain', 'Disable Lock'),
                'url' => '#',
                'linkOptions' => ['data-action' => Url::to(['@domain/disable-lock'])],
            ],
            // Autorenew
            '<li role="presentation" class="divider"></li>',
            [
                'label' => '<i class="fa fa-toggle-on"></i> ' . Yii::t('hipanel:domain', 'Enable autorenew'),
                'url' => '#',
                'linkOptions' => ['data-action' => Url::to(['@domain/enable-autorenewal'])],
            ],
            [
                'label' => '<i class="fa fa-toggle-off"></i> ' . Yii::t('hipanel:domain', 'Disable autorenew'),
                'url' => '#',
                'linkOptions' => ['data-action' => Url::to(['@domain/disable-autorenewal'])],
            ],
            [
                'label' => '<i class="fa fa-fw fa-envelope-o"></i>' . Yii::t('hipanel:domain', 'Send FOA'),
                'url' => '#bulk-force-notify-transfer-in-modal',
                'linkOptions' => ['data-toggle' => 'modal'],
                'visible' => Yii::$app->user->can('domain.force-send-foa'),
            ],
        ];
    }
}
