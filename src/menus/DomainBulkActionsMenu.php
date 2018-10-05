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

use hipanel\widgets\AjaxModal;
use Yii;
use yii\bootstrap\Dropdown;
use yii\bootstrap\Modal;
use yii\helpers\Html;

class DomainBulkActionsMenu extends \hiqdev\yii2\menus\Menu
{
    public function items()
    {
        return [
            [
                'label' => '
                    <div class="dropdown" style="display: inline-block">
                        <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            ' . Yii::t('hipanel', 'Basic actions') . '
                            <span class="caret"></span>
                        </button>
                        ' . DomainBulkBasicActionsMenu::widget([], [
                            'class' => Dropdown::class,
                            'encodeLabels' => false,
                        ]) . '
                    </div>
                ',
            ],
            [
                'label' => AjaxModal::widget([
                    'id' => 'bulk-domain-push-modal',
                    'bulkPage' => true,
                    'header' => Html::tag('h4', Yii::t('hipanel:domain', 'Push'), ['class' => 'modal-title']),
                    'scenario' => 'domain-push-modal',
                    'actionUrl' => ['domain-push-modal'],
                    'size' => Modal::SIZE_LARGE,
                    'toggleButton' => false,
                ]),
            ],
            [
                'label' =>  AjaxModal::widget([
                    'id' => 'bulk-set-note-modal',
                    'bulkPage' => true,
                    'header' => Html::tag('h4', Yii::t('hipanel:domain', 'Set notes'), ['class' => 'modal-title']),
                    'scenario' => 'bulk-set-note',
                    'actionUrl' => ['bulk-set-note'],
                    'size' => Modal::SIZE_LARGE,
                    'toggleButton' => ['label' => Yii::t('hipanel:domain', 'Set notes'), 'class' => 'btn btn-sm btn-default'],
                ]),
            ],
            [
                'label' =>  AjaxModal::widget([
                    'id' => 'bulk-set-nss-modal',
                    'bulkPage' => true,
                    'header' => Html::tag('h4', Yii::t('hipanel:domain', 'Set NS'), ['class' => 'modal-title']),
                    'scenario' => 'bulk-set-nss',
                    'actionUrl' => ['bulk-set-nss'],
                    'size' => Modal::SIZE_LARGE,
                    'toggleButton' => ['label' => Yii::t('hipanel:domain', 'Set NS'), 'class' => 'btn btn-sm btn-default'],
                ]),
            ],
            [
                'label' =>  AjaxModal::widget([
                    'id' => 'bulk-change-contacts-modal',
                    'bulkPage' => true,
                    'header' => Html::tag('h4', Yii::t('hipanel:domain', 'Change contacts'), ['class' => 'modal-title']),
                    'scenario' => 'bulk-set-contacts',
                    'actionUrl' => ['bulk-set-contacts-modal'],
                    'size' => Modal::SIZE_LARGE,
                    'toggleButton' => ['label' => Yii::t('hipanel:domain', 'Change contacts'), 'class' => 'btn btn-sm btn-default'],
                ]),
            ],
            [
                'label' => AjaxModal::widget([
                    'id' => 'bulk-force-notify-transfer-in-modal',
                    'bulkPage' => true,
                    'header' => Html::tag('h4', Yii::t('hipanel:domain', 'Send FOA to specific email')),
                    'scenario' => 'force-notify-transfer-in-modal',
                    'actionUrl' => ['@domain/force-notify-transfer-in-modal'],
                    'size' => Modal::SIZE_DEFAULT,
                    'toggleButton' => [
                        'label' => Yii::t('hipanel:domain', 'Send FOA'),
                        'class' => 'btn btn-sm btn-default',
                    ],
                ]),
                'visible' => Yii::$app->user->can('domain.force-send-foa'),
            ],

        ];
    }
}
