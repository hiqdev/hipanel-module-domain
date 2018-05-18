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

use hipanel\modules\domain\models\Domain;
use hipanel\widgets\AjaxModal;
use Yii;
use yii\bootstrap\Modal;
use yii\helpers\Html;

class DomainDetailMenu extends \hipanel\menus\AbstractDetailMenu
{
    public $model;

    public function items()
    {
        $actions = DomainActionsMenu::create([
            'model' => $this->model,
        ])->items();

        $items = array_merge($actions, [
            [
                'label' => AjaxModal::widget([
                    'id' => 'push-modal-link',
                    'header' => Html::tag('h4', Yii::t('hipanel:domain', 'Push domain') . ': ' . Html::tag('b', $this->model->domain), ['class' => 'modal-title']),
                    'scenario' => 'push',
                    'actionUrl' => ['domain-push-modal', 'id' => $this->model->id],
                    'size' => Modal::SIZE_DEFAULT,
                    'toggleButton' => [
                        'label' => '<i class="fa fa-fw fa-exchange"></i>' . Yii::t('hipanel:domain', 'Push domain'),
                        'class' => 'clickable',
                        'data-pjax' => 0,
                        'tag' => 'a',
                    ],
                ]),
                'encode' => false,
                'visible' => $this->model->canBePushed(),
            ],
            [
                'visible' => $this->model->canRenew(),
                'label' => Yii::t('hipanel:domain', 'Renew domain'),
                'icon' => 'fa-forward',
                'url' => ['add-to-cart-renewal', 'model_id' => $this->model->id],
                'linkOptions' => [
                    'data-pjax' => 0,
                ],
            ],
            [
                'label' => Yii::t('hipanel', 'Delete'),
                'icon' => 'fa-trash',
                'url' => ['@domain/delete', 'id' => $this->model->id],
                'linkOptions' => [
                    'data' => [
                        'confirm' => Yii::t('hipanel:domain', 'Are you sure you want to delete domain {domain}?', ['domain' => $this->model->domain]),
                        'method' => 'post',
                        'pjax' => '0',
                    ],
                ],
                'visible' => $this->model->canDelete(),
            ],
        ]);

        unset($items['view']);

        return $items;
    }
}
