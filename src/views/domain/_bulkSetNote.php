<?php

use hipanel\helpers\Url;
use hipanel\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>
<?php Pjax::begin(['id' => 'bulkNote-pjax-container', 'enablePushState' => false, 'enableReplaceState' => false]) ?>
<?php $this->registerJs("
var saveButton = $('#bulkNote-save-button');
$(document).on('pjax:send', function(event) {
    event.preventDefault()
    saveButton.button('loading');

});
$(document).on('pjax:complete', function(event) {
  event.preventDefault();
  saveButton.button('reset');
});
"); ?>
<?php $form = ActiveForm::begin([
    'id' => 'bulk-set-note',
    'action' => Url::toRoute('set-note'),
    'validateOnBlur' => true,
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => 'set-note']),
    'options' => [
        'data-pjax' => true,
        'data-pjaxPush' => false,
    ],
]) ?>

<div>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#bulk" aria-controls="home" role="tab" data-toggle="tab"><?= Yii::t('app', 'Bulk') ?></a></li>
        <li role="presentation"><a href="#by-one" aria-controls="profile" role="tab" data-toggle="tab"><?= Yii::t('app', 'By one') ?></a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="bulk">
            <div class="row" style="margin-top: 15pt;">
                <div class="col-md-12">
                    <?= Html::textInput("note", null, ['class' => 'form-control', 'placeholder' => Yii::t('app', 'Type your note here')]); ?>
                    <br>
                    <?php foreach ($models as $model) : ?>
                        <?= Html::activeHiddenInput($model, "ids[]") ?>
                        <span class="label label-primary"><?= $model->domain ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="by-one">
            <div class="row" style="margin-top: 15pt;">
                <?php foreach ($models as $model) : ?>
                    <div class="col-md-6">
                        <?= Html::activeHiddenInput($model, "[$model->id]id") ?>
                        <?= $form->field($model, "[$model->id]domain")->textInput(['disabled' => true, 'readonly' => true])->label(false) ?>
                    </div>
                    <!-- /.col-md-6 -->
                    <div class="col-md-6">
                        <?= $form->field($model, "note")->label(false); ?>
                    </div>
                    <!-- /.col-md-6 -->
                <?php endforeach; ?>
            </div>
        </div>
    </div>

</div>
    <hr>
<?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success', 'id' => 'bulkNote-save-button']) ?>
<?php ActiveForm::end() ?>
<?php Pjax::end(); ?>