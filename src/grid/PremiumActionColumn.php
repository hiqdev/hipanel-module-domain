<?php

namespace hipanel\modules\domain\grid;

use hipanel\grid\ActionColumn;
use hipanel\widgets\ModalButton;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class PremiumActionColumn extends ActionColumn
{
    /**
     * @var boolean
     */
    public $is_premium;

    public function init()
    {
        $this->template = '{update} {delete}';
        $this->visibleButtonsCount = 2;
        $this->options = [
            'style' => 'width: 15%',
        ];
        $this->buttons = [
            'update' => function ($url, $model, $key) {
                if (!$this->is_premium) {
                    $html = Html::button('<i class="fa fa-pencil"></i> ' . Yii::t('hipanel', 'Update'), [
                        'class' => 'btn btn-default btn-xs disabled',
                    ]);
                }

                $html = Html::button('<i class="fa fa-pencil"></i> ' . Yii::t('hipanel', 'Update'), [
                    'class' => 'btn btn-default btn-xs edit-dns-toggle',
                    'data' => [
                        'id' => $model->id,
                        'load-url' => Url::to([
                            '@domain/premium-row-update',
                            'id' => $model->id,
                            'type' => '',
                        ]),
                    ],
                ]);

                return $html;

            },
            'delete' => function ($url, $model, $key) {
                if (!$this->is_premium) {
                    return Html::button('<i class="fa text-danger fa-trash-o"></i> ' . Yii::t('hipanel', 'Delete'), [
                        'class' => 'btn btn-default btn-xs disabled',
                    ]);
                }

                return ModalButton::widget([
                    'model' => $model,
                    'scenario' => 'delete',
                    'submit' => ModalButton::SUBMIT_PJAX,
                    'form' => [
                        'action' => '#', // Url::to('@domain/delete-urlfw'),
                    ],
                    'button' => [
                        'class' => 'btn btn-default btn-xs',
                        'label' => '<i class="fa text-danger fa-trash-o"></i> ' . Yii::t('hipanel', 'Delete'),
                    ],
                    'modal' => [
                        'header' => Html::tag('h4', Yii::t('hipanel:domain', 'Confirm record deleting'), ['class' => 'modal-title']),
                        'headerOptions' => ['class' => 'label-danger'],
                        'footer' => [
                            'label' => Yii::t('hipanel:dns', 'Delete record'),
                            'data-loading-text' => Yii::t('hipanel:dns', 'Deleting record...'),
                            'class' => 'btn btn-danger',
                        ],
                    ],
                    'body' => function ($model) {
                        echo Html::activeHiddenInput($model, 'id');
                        echo Yii::t('hipanel:dns', 'Are you sure, that you want to delete record?');
                    },
                ]);
            },
        ];
    }
}
