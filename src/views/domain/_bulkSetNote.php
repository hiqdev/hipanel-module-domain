<?php

use hipanel\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>

<?php $form = ActiveForm::begin([
    'id' => 'bulk-set-note',
    'action' => Url::toRoute('set-note'),
    'validateOnBlur' => true,
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => 'set-note']),
]) ?>

<div>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#bulk" aria-controls="home" role="tab" data-toggle="tab"><?= Yii::t('app', 'Set for all') ?></a></li>
        <li role="presentation"><a href="#by-one" aria-controls="profile" role="tab" data-toggle="tab"><?= Yii::t('app', 'Edit by one') ?></a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="bulk">
            <div class="row" style="margin-top: 15pt;">
                <div class="col-md-12">
                    <?= Html::textInput("bulk_note", null, ['class' => 'form-control', 'placeholder' => Yii::t('app', 'Type your note here')]); ?>
                    <br>
                    <?php foreach ($models as $model) : ?>
                        <span><?= $model->domain ?></span>,&nbsp;&nbsp;
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="by-one">
            <div class="row" style="margin-top: 15pt;">
                <?php foreach ($models as $model) : ?>
                    <div class="col-md-4 text-right">
                        <?= Html::activeHiddenInput($model, "[$model->id]id") ?>
                        <?= $model->domain ?>
                    </div>
                    <!-- /.col-md-6 -->
                    <div class="col-md-8">
                        <?= $form->field($model, "[$model->id]note")->label(false); ?>
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