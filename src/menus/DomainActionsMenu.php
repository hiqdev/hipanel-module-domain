<?php

namespace hipanel\modules\domain\menus;

use Yii;
use yii\helpers\Url;

class DomainActionsMenu extends \hiqdev\menumanager\Menu
{
    public function items()
    {
        return [
            [
                'label' => '<i class="fa fa-fw fa-info"></i> ' . Yii::t('hipanel', 'View'),
                'url' => Url::to(['@domain/view', 'id' => $this->model->id]),
                'encode' => false,
            ],
            [
                'label' => '<i class="fa fa-envelope-o"></i> ' . Yii::t('hipanel/domain', 'Send FOA again'),
                'url' => Url::to(['@domain/notify-transfer-in', 'id' => $this->model->id]),
                'options' => [
                    'data' => [
                        'method' => 'post',
                        'data-pjax' => '0',
                    ],
                ],
                'encode' => false,
                'visible' => $this->model->state === 'preincoming',
            ],
            [
                'label' => '<i class=""></i> ' . Yii::t('hipanel/domain', 'approve-preincoming'),
                'url' => '#',
                'visible' => false,
                'encode' => false,
            ],
            [
                'label' => '<i class=""></i> ' . Yii::t('hipanel/domain', 'reject-preincoming'),
                'url' => '#',
                'visible' => false,
                'encode' => false,
            ],
            [
                'label' => '<i class="fa fa-fw fa-trash"></i> ' . Yii::t('hipanel', 'Delete'),
                'url' => Url::to(['@domain/delete', 'id' => $this->model->id]),
                'options' => [
                    'data' => [
                        'confirm' => Yii::t('hipanel', 'Are you sure you want to delete this item?'),
                        'method' => 'POST',
                        'data-pjax' => '0',
                    ],
                ],
                'encode' => false,
            ],
        ];
    }
}
