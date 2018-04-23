<?php

use hipanel\helpers\Url;
use hipanel\modules\domain\widgets\NsWidget;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>

<div>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#bulk" aria-controls="home" role="tab" data-toggle="tab"><?= Yii::t('hipanel', 'Set for all') ?></a></li>
        <li role="presentation"><a href="#by-one" aria-controls="profile" role="tab" data-toggle="tab"><?= Yii::t('hipanel', 'Set by one') ?></a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="bulk">
            <div class="row" style="margin-top: 15pt;">
                <div class="col-md-12">
                    <?= NsWidget::widget([
                        'model' => $models,
                        'attribute' => 'nsips',
                        'actionUrl' => 'bulk-set-nss',
                    ]); ?>
                    <br>
                    <div class="panel panel-default">
                        <div class="panel-heading"><?= Yii::t('hipanel:domain', 'Affected domains') ?></div>
                        <div class="panel-body">
                            <?= \hipanel\widgets\ArraySpoiler::widget([
                                'data' => $models,
                                'visibleCount' => count($models),
                                'formatter' => function ($model) {
                                    return $model->domain;
                                },
                                'delimiter' => ',&nbsp; ',
                            ]); ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="by-one">
            <?php $form = ActiveForm::begin([
                'id' => 'bulk-set-nss',
                'action' => Url::toRoute('set-nss'),
                'enableAjaxValidation' => true,
                'validateOnBlur' => true,
                'validationUrl' => Url::toRoute(['validate-form', 'scenario' => 'OLD-set-ns']),
            ]) ?>
            <div class="row" style="margin-top: 15pt;">
                <?php foreach ($models as $model) : ?>
                    <div class="col-md-4 text-right" style="line-height: 34px;">
                        <?= Html::activeHiddenInput($model, "[$model->id]id") ?>
                        <?= $model->domain ?>
                    </div>
                    <!-- /.col-md-6 -->
                    <div class="col-md-8">
                        <?= $form->field($model, "[$model->id]nsips")->textInput(['readonly' => $model->state !== $model::STATE_OK])->label(false); ?>
                    </div>
                    <!-- /.col-md-6 -->
                <?php endforeach; ?>
            </div>
            <hr>
            <?= Html::submitButton(Yii::t('hipanel', 'Save'), ['class' => 'btn btn-success', 'id' => 'save-button']) ?>
            <?php ActiveForm::end() ?>
        </div>
    </div>

</div>
