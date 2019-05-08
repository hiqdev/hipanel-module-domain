<?php

use hipanel\modules\domain\widgets\combo\RegistryCombo;
use hipanel\widgets\Box;
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

        <div class="row">
            <div class="col-md-6">

                <?php Box::begin() ?>

                <?php if (!$model->isNewRecord) : ?>
                    <?= Html::activeHiddenInput($model, 'id') ?>
                <?php endif; ?>

                <div class="col-md-4 col-sm-6 col-xs-12">
                    <?= $form->field($model, 'registry')->widget(RegistryCombo::class) ?>
                </div>

                <div class="col-md-4 col-sm-6 col-xs-12">
                    <?= $form->field($model, 'state')->dropDownList($model->typeList) ?>
                </div>

                <div class="col-md-4 col-sm-6 col-xs-12">
                    <?= $form->field($model, 'name') ?>
                </div>

                <div class="col-md-4 col-sm-6 col-xs-12">
                    <?= $form->field($model, 'no') ?>
                </div>

                <div class="col-md-4 col-sm-6 col-xs-12">
                    <?= $form->field($model, 'add_period') ?>
                </div>

                <div class="col-md-4 col-sm-6 col-xs-12">
                    <?= $form->field($model, 'add_limit') ?>
                </div>

                <div class="col-md-4 col-sm-6 col-xs-12">
                    <?= $form->field($model, 'has_contacts')->checkbox(); ?>
                    <?= $form->field($model, 'password_required')->checkbox(); ?>
                </div>

                <?php Box::end() ?>

            </div>
        </div>
<!--        --><?php //DynamicFormWidget::begin([
//            'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
//            'widgetBody' => '.container-items', // required: css class selector
//            'widgetItem' => '.item', // required: css class
//            'limit' => 4, // the maximum times, an element can be cloned (default 999)
//            'min' => 1, // 0 or 1 (default 1)
//            'insertButton' => '.add-item', // css class
//            'deleteButton' => '.remove-item', // css class
//            'model' => reset($models),
//            'formId' => 'dynamic-form',
//            'formFields' => [
//                'host',
//                'ips',
//            ],
//        ]) ?>

<!--        <div class="container-items">-->
<!--            --><?php //foreach ($models as $i => $model) : ?>
<!--                <div class="item">
                    <?php //Box::begin() ?>
                    <?php
                    // necessary for update action.
//                    if (!$model->isNewRecord) {
//                        $model->setScenario('update');
//                        echo Html::activeHiddenInput($model, "[$i]id");
//                    }
//                    ?>-->
<!--                    <div class="row input-row">-->
<!--                        <div class="col-sm-5">-->
<!--                            --><?php //if ($model->isNewRecord) : ?>
<!--                                --><?//= $form->field($model, "[$i]host")->textInput(['placeholder' => Yii::t('hipanel', 'Name server')])->label(false) ?>
<!--                            --><?php //else : ?>
<!--                                <p class="form-control-static text-center">--><?//= $model->host; ?><!--</p>-->
<!--                            --><?php //endif; ?>
<!--                        </div>-->
<!--                        <div class="col-sm-5">-->
<!--                            --><?//= $form->field($model, "[$i]ips")->widget(StaticCombo::class, [
//                                'formElementSelector' => '.item',
//                                'multiple' => true,
//                                'selectAllButton' => false,
//                                'data' => $model->ips ? array_combine($model->ips, $model->ips) : [],
//                                'pluginOptions' => [
//                                    'select2Options' => [
//                                        'placeholder' => Yii::t('hipanel', 'IP addresses'),
//                                        'tags' => true,
//                                        'tokenSeparators' => [';', ',', ' '],
//                                        'minimumResultsForSearch' => new JsExpression('Infinity'),
//                                        'createTag' => new JsExpression("function (query) {
//                                            return {
//                                                id: query.term,
//                                                text: query.term,
//                                                tag: true
//                                            };
//                                        }"),
//                                        'language' => [
//                                            'noResults' => new JsExpression("function (params) {
//                                                return " . Json::encode(Yii::t('hipanel:domain', 'Up to 13 IPv4 or IPv6 addresses separated with comma')) . ";
//                                            }")
//                                        ]
//                                    ],
//                                ],
//                            ])->label(false)->hint(Yii::t('hipanel:domain', 'Up to 13 IPv4 or IPv6 addresses separated with comma')) ?>
<!--                        </div>-->
<!--                        <div class="col-sm-2">-->
<!--                            --><?php //if ($model->isNewRecord) : ?>
<!--                                <div class="btn-group">-->
<!--                                    <button type="button" class="add-item btn btn-success btn-sm"><i-->
<!--                                            class="glyphicon glyphicon-plus"></i></button>-->
<!--                                    <button type="button" class="remove-item btn btn-danger btn-sm"><i-->
<!--                                            class="glyphicon glyphicon-minus"></i></button>-->
<!--                                </div>-->
<!--                            --><?php //endif; ?>
<!--                        </div>-->
<!--                    </div>-->
<!--                    --><?php //Box::end() ?>
<!--                </div>-->
<!--            --><?php //endforeach; ?>
<!--        </div>-->
<!--        --><?php //DynamicFormWidget::end() ?>
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
