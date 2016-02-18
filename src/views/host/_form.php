<?php

use wbraganca\dynamicform\DynamicFormWidget;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<?php $form = ActiveForm::begin([
    'id' => 'dynamic-form',
    'enableClientValidation' => true,
    'validateOnBlur' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => reset($models)->isNewRecord ? 'create' : 'update']),
]) ?>

<?php DynamicFormWidget::begin([
    'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
    'widgetBody' => '.container-items', // required: css class selector
    'widgetItem' => '.item', // required: css class
    'limit' => 4, // the maximum times, an element can be cloned (default 999)
    'min' => 1, // 0 or 1 (default 1)
    'insertButton' => '.add-item', // css class
    'deleteButton' => '.remove-item', // css class
    'model' => reset($models),
    'formId' => 'dynamic-form',
    'formFields' => [
        'host',
    ],
]) ?>

<div class="container-items"><!-- widgetContainer -->
    <?php foreach ($models as $i => $model) : ?>
        <div class="item"><!-- widgetBody -->
            <?php
            // necessary for update action.
            if (!$model->isNewRecord) {
                $model->setScenario('update');
                echo Html::activeHiddenInput($model, "[$i]id");
            }
            ?>
            <div class="row input-row margin-bottom">
                <div class="col-sm-5">
                    <?php if ($model->isNewRecord) : ?>
                        <?= $form->field($model, "[$i]host")->textInput(['placeholder' => Yii::t('app', 'Name server')])->label(false) ?>
                    <?php else : ?>
                        <p class="form-control-static text-center"><?= $model->host; ?></p>
                    <?php endif; ?>
                </div>
                <div class="col-sm-5">
                    <?= $form->field($model, "[$i]ips")->textInput([
                        'placeholder' => Yii::t('app', 'IP addresses'),
                        'value' => implode(', ', (array) $model->ips),
                    ])->label(false)->hint(Yii::t('hipanel/domain', 'Up to 13 IPv4 or IPv6 addresses separated with comma')) ?>
                </div>
                <div class="col-sm-2">
                    <?php if ($model->isNewRecord) : ?>
                        <button type="button" class="add-item btn btn-success btn-sm"><i class="glyphicon glyphicon-plus"></i></button>
                        <button type="button" class="remove-item btn btn-danger btn-sm"><i class="glyphicon glyphicon-minus"></i></button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php DynamicFormWidget::end() ?>
<?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
&nbsp;
<?= Html::button(Yii::t('app', 'Cancel'), ['class' => 'btn btn-default', 'onclick' => 'history.go(-1)']) ?>
<?php ActiveForm::end() ?>

