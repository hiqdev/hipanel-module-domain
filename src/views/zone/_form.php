<?php

use hipanel\modules\domain\widgets\combo\RegistryCombo;
use hipanel\widgets\Box;
use hipanel\widgets\DynamicFormWidget;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var \yii\web\View $this
 * @var \hipanel\modules\domain\models\Zone $model
 */

?>

<div class="row">
    <div class="col-md-12">
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
            'limit' => 99, // the maximum times, an element can be cloned (default 999)
            'min' => 1, // 0 or 1 (default 1)
            'insertButton' => '.add-item', // css class
            'deleteButton' => '.remove-item', // css class
            'model' => reset($models),
            'formId' => 'dynamic-form',
            'formFields' => [
                'registry',
                'state',
                'name',
                'no',
                'add_period',
                'add_limit',
                'has_contacts',
                'password_required',
            ]
        ]) ?>
        <div class="container-items"><!-- widgetContainer -->
            <?php foreach ($models as $i => $model) : ?>
                <?php
                if ($model->scenario == 'update') {
                    $model->setScenario('update');
                    echo Html::activeHiddenInput($model, "[$i]id");
                }
                ?>
                <div class="item">
                    <?php Box::begin() ?>

                    <div class="row">
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <?= $form->field($model, "[$i]registry")->widget(RegistryCombo::class) ?>
                        </div>

                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <?= $form->field($model, "[$i]state")->dropDownList($model->typeList) ?>
                        </div>

                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <?= $form->field($model, "[$i]name") ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <?= $form->field($model, "[$i]no") ?>
                        </div>

                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <?= $form->field($model, "[$i]add_period") ?>
                        </div>

                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <?= $form->field($model, "[$i]add_limit") ?>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <?= $form->field($model, "[$i]has_contacts")->checkbox(); ?>
                        <?= $form->field($model, "[$i]password_required")->checkbox(); ?>
                    </div>
                    <?php Box::end() ?>
                </div>
            <?php endforeach ?>
        </div>
    </div>

    <?php DynamicFormWidget::end() ?>
    <div class="row">
        <div class="col-md-12 no">
            <?= Html::submitButton(Yii::t('hipanel', 'Save'), ['class' => 'btn btn-success']) ?>
            &nbsp;
            <?= Html::button(Yii::t('hipanel', 'Cancel'), ['class' => 'btn btn-default', 'onclick' => 'history.go(-1)']) ?>
        </div>
    </div>
    <?php ActiveForm::end() ?>
    </div>
</div>
