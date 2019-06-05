<?php

namespace hipanel\modules\domain\menus;

use hipanel\menus\AbstractDetailMenu;
use hipanel\modules\domain\models\Zone;
use Yii;

class ZoneDetailMenu extends AbstractDetailMenu
{
    /**
     * @var Zone $model
     */
    public $model;

    public function items()
    {
        return [
            [
                'label' => Yii::t('hipanel', 'Update'),
                'icon' => 'fa-pencil',
                'url' => ['@zone/update', 'id' => $this->model->id],
                'visible' => Yii::$app->user->can('zone.update'),
            ],
            [
                'label' => Yii::t('hipanel', 'Delete'),
                'icon' => 'fa-trash',
                'url' => ['@zone/delete', 'id' => $this->model->id],
                'visible' => Yii::$app->user->can('zone.delete'),
                'linkOptions' => [
                    'data' => [
                        'method' => 'post',
                        'pjax' => '0',
                        'form' => 'delete',
                        'confirm' => Yii::t('hipanel:domain', 'Are you sure you want to delete this zone?'),
                        'params' => [
                            'Order[id]' => $this->model->id,
                        ],
                    ],
                ],
            ],
        ];
    }
}
