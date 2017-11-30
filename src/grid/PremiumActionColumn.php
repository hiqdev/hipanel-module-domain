<?php

namespace hipanel\modules\domain\grid;

use hipanel\grid\ActionColumn;
use hipanel\widgets\ModalButton;
use Yii;
use yii\bootstrap\Progress;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

class PremiumActionColumn extends ActionColumn
{
    /**
     * @var boolean|null
     */
    public $is_premium;

    /**
     * @var int
     */
    public $fieldsCount = 4;

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
                    return Html::button('<i class="fa fa-pencil"></i> ' . Yii::t('hipanel', 'Update'), [
                        'class' => 'btn btn-default btn-xs disabled',
                    ]);
                }

                $html = Html::button('<i class="fa fa-pencil"></i> ' . Yii::t('hipanel', 'Update'), [
                    'class' => 'btn btn-default btn-xs edit-premium-toggle',
                    'data' => [
                        'id' => $model->id,
                        'load-url' => Url::to([
                            '@domain/inline-premium-feature-form',
                            'domainId' => $model->domain_id,
                            'featureId' => $model->id,
                            'for' => $this->getFeatureName($model),
                        ]),
                    ],
                ]);

                $progress = Json::htmlEncode("<tr><td class='pf-update-from-td' colspan='{$this->fieldsCount}' style='overflow:hidden; padding: 4px 0;'>" . Progress::widget([
                        'id' => 'progress-bar',
                        'percent' => 100,
                        'barOptions' => ['class' => 'active progress-bar-striped', 'style' => 'width: 100%'],
                    ]) . '</td></tr>');

                Yii::$app->view->registerJs(/** @lang JavaScript */
                    "
                    $('.edit-premium-toggle').click(function () {
                        var id = $(this).data('id');

                        var currentRow = $(this).closest('tr');
                        var newRow = $($progress);

                        $(newRow).data({'id': id});
                        $('tr').find('.pf-update-form-close').click();
                        $(newRow).insertAfter(currentRow);

                        jQuery.ajax({
                            url: $(this).data('load-url'),
                            type: 'GET',
                            timeout: 0,
                            error: function() {

                            },
                            success: function(data) {
                                newRow.find('td').html(data);
                                newRow.find('.btn-cancel').on('click', function (event) {
                                    event.preventDefault();
                                    newRow.remove();
                                });
                            }
                        });

                    });
                ");

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
                        'action' => Url::to(['@domain/set-premium-feature', 'for' => $this->getFeatureName($model)]),
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
                        $model->status = 'deleted';
                        $model->type = $this->getFeatureName($model);
                        echo Html::activeHiddenInput($model, 'domain_id');
                        echo Html::activeHiddenInput($model, 'status');
                        echo Html::activeHiddenInput($model, 'type');
                        echo Yii::t('hipanel:domain', 'Are you sure, that you want to delete record?');
                    },
                ]);
            },
        ];
    }

    private function getFeatureName($model)
    {
        return strtolower((new \ReflectionClass($model))->getShortName());
    }
}
