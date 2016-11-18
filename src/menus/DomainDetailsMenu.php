<?php

namespace hipanel\modules\domain\menus;

use hipanel\modules\domain\models\Domain;
use hipanel\widgets\AjaxModal;
use Yii;
use yii\bootstrap\Modal;
use yii\helpers\Html;

class DomainDetailsMenu extends \hiqdev\menumanager\Menu
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
                    'header' => Html::tag('h4', Yii::t('hipanel:domain', 'Push domain') . ': ' . Html::tag('b', $this->title), ['class' => 'modal-title']),
                    'scenario' => 'push',
                    'actionUrl' => ['domain-push-modal', 'id' => $this->model->id],
                    'size' => Modal::SIZE_DEFAULT,
                    'toggleButton' => [
                        'label' => '<i class="fa fa-fw fa-paper-plane-o"></i>' . Yii::t('hipanel:domain', 'Push domain'),
                        'class' => 'clickable',
                        'data-pjax' => 0,
                        'tag' => 'a',
                    ],
                ]),
                'encode' => false,
            ],
            [
                'visible' => $this->model->canRenew() && Yii::$app->user->can('domain.pay'),
                'label' => Yii::t('hipanel:domain', 'Renew domain'),
                'icon' => 'forward',
                'url' => ['add-to-cart-renewal', 'model_id' => $this->model->id],
                'linkOptions' => [
                    'data-pjax' => 0,
                ],
            ],
            [
                'label' => Yii::t('hipanel', 'Delete'),
                'url' => ['@domain/delete', 'id' => $this->model->id],
                'linkOptions' => [
                    'data' => [
                        'confirm' => Yii::t('hipanel:domain', 'Are you sure you want to delete domain {domain}?', ['domain' => $this->model->domain]),
                        'method' => 'post',
                        'data-pjax' => '0',
                    ]
                ],
            ],
        ]);

        unset($items['view']);

        return $items;
    }
}
