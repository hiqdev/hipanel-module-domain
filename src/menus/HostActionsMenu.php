<?php

namespace hipanel\modules\domain\menus;

use Yii;

class HostActionsMenu extends \hiqdev\yii2\menus\Menu
{
    public $models;

    public function items()
    {
        return [
            [
                'label' => Yii::t('hipanel', 'Delete'),
                'icon' => 'fa-trash',
                'url' => ['@host/delete', 'id' => $this->model->id],
                'linkOptions' => [
                    'data' => [
                        'confirm' => Yii::t('hipanel', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                        'pjax' => '0',
                    ]
                ],
            ],
        ];
    }
}
