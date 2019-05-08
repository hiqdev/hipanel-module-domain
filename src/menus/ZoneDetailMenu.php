<?php

namespace hipanel\modules\domain\menus;

use hipanel\menus\AbstractDetailMenu;
use Yii;

class ZoneDetailMenu extends AbstractDetailMenu
{
    public $model;

    public function items()
    {
        return [
            [
                'label' => Yii::t('hipanel:domain', 'Update'),
                'icon' => 'fa-pencil',
                'url' => ['@zone/update', 'id' => $this->model->id],
//                'visible' => Yii::$app->user->can('order.update'),
            ],
            [
                'label' => Yii::t('hipanel:domain', 'Delete'),
                'icon' => 'fa-trash',
                'url' => ['@zone/delete', 'id' => $this->model->id],
//                'visible' => Yii::$app->user->can('order.delete'),
                'linkOptions' => [
                    'data' => [
                        'method' => 'post',
                        'pjax' => '0',
                        'form' => 'delete',
                        'confirm' => Yii::t('hipanel:domain', 'Are you sure you want to delete this model?'),
                        'params' => [
                            'Order[id]' => $this->model->id,
                        ],
                    ],
                ],
            ],
        ];
    }
}
