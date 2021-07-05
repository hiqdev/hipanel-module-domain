<?php

use hipanel\modules\domain\models\Secdns;
use hipanel\modules\domain\widgets\combo\DomainCombo;
use hipanel\widgets\Box;
use hipanel\widgets\DynamicFormWidget;
use hiqdev\combo\StaticCombo;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * @var \yii\web\View
 */

$this->title = Yii::t('hipanel:domain', 'Create SecDNS record');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel', 'SecDNS'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
            'limit' => 4, // the maximum times, an element can be cloned (default 999)
            'min' => 1, // 0 or 1 (default 1)
            'insertButton' => '.add-item', // css class
            'deleteButton' => '.remove-item', // css class
            'model' => reset($models),
            'formId' => 'dynamic-form',
            'formFields' => [
                'domain_id',
                'key_tag',
                'digest_alg',
                'digest_type',
                'digest',
                'key_alg',
                'public_key',
            ],
        ]) ?>

        <div class="container-items"><!-- widgetContainer -->
            <?php foreach ($models as $i => $model) : ?>
                <div class="item"><!-- widgetBody -->
                    <?php Box::begin() ?>
                    <?php
                    // necessary for update action.
                    if (!$model->isNewRecord) {
                        $model->setScenario('update');
                        echo Html::activeHiddenInput($model, "[$i]id");
                    }
                    ?>
                    <div class="row input-row">
                        <div class="col-sm-5">
                            <?= $form->field($model, "[$i]domain_id")->widget(DomainCombo::class)->label(false) ?>
                        </div>
                        <div class="col-sm-5">
                            <?= $form->field($model, "[$i]key_tag")->textInput(['placeholder' => Yii::t('hipanel:domain', 'Key tag')])->label(false) ?>
                        </div>
                        <div class="col-sm-5">
                            <?= $form->field($model, "[$i]digest_alg")->widget(StaticCombo::class, [
                                'data' => Secdns::algorithmTypesWithLabels(),
                                'hasId' => true,
                                'formElementSelector' => '.item',
                                'multiple' => false,
                                'selectAllButton' => false,
                                'inputOptions' => [
                                    'value' => $model->digest_alg,
                                    'class' => 'form-control',
                                ],
                                'pluginOptions' => [
                                    'select2Options' => [
                                        'placeholder' => Yii::t('hipanel:domain', 'Digest algorithm'),
                                    ],
                                ],
                            ])->label(false) ?>
                        </div>
                        <div class="col-sm-5">
                            <?= $form->field($model, "[$i]digest_type")->widget(StaticCombo::class, [
                                'data' => Secdns::digestTypesWithLabels(),
                                'hasId' => true,
                                'formElementSelector' => '.item',
                                'multiple' => false,
                                'selectAllButton' => false,
                                'inputOptions' => [
                                    'value' => $model->digest_type,
                                    'class' => 'form-control',
                                ],
                                'pluginOptions' => [
                                    'select2Options' => [
                                        'placeholder' => Yii::t('hipanel:domain', 'Digest type'),
                                    ],
                                ],
                            ])->label(false) ?>
                        </div>
                        <div class="col-sm-5">
                            <?= $form->field($model, "[$i]digest")->textInput(['placeholder' => Yii::t('hipanel:domain', "Digest")])->label(false) ?>
                        </div>
                        <div class="col-sm-5">
                            <?= $form->field($model, "[$i]key_alg")->widget(StaticCombo::class, [
                                'data' => Secdns::algorithmTypesWithLabels(),
                                'hasId' => true,
                                'formElementSelector' => '.item',
                                'multiple' => false,
                                'selectAllButton' => false,
                                'inputOptions' => [
                                    'value' => $model->key_alg,
                                    'class' => 'form-control',
                                ],
                                'pluginOptions' => [
                                    'select2Options' => [
                                        'placeholder' => Yii::t('hipanel:domain', 'Key algorithm'),
                                    ],
                                ],
                            ])->label(false) ?>
                        </div>
                        <div class="col-sm-5">
                            <?= $form->field($model, "[$i]key_flags")->widget(StaticCombo::class, [
                                'data' => Secdns::flagTypesWithLabels(),
                                'hasId' => true,
                                'formElementSelector' => '.item',
                                'multiple' => false,
                                'selectAllButton' => false,
                                'inputOptions' => [
                                    'value' => $model->key_alg,
                                    'class' => 'form-control',
                                ],
                                'pluginOptions' => [
                                    'select2Options' => [
                                        'placeholder' => Yii::t('hipanel:domain', 'Key flag'),
                                    ],
                                ],
                            ])->label(false) ?>
                        </div>
                        <div class="col-sm-5">
                            <?= $form->field($model, "[$i]key_protocol")->widget(StaticCombo::class, [
                                'data' => Secdns::protocolTypesWithLabels(),
                                'hasId' => true,
                                'formElementSelector' => '.item',
                                'multiple' => false,
                                'selectAllButton' => false,
                                'inputOptions' => [
                                    'value' => $model->key_alg,
                                    'class' => 'form-control',
                                ],
                                'pluginOptions' => [
                                    'select2Options' => [
                                        'placeholder' => Yii::t('hipanel:domain', 'Key protocol'),
                                    ],
                                ],
                            ])->label(false) ?>
                        </div>
                        <div class="col-sm-5">
                            <?= $form->field($model, "[$i]pub_key")->textInput(['placeholder' => Yii::t('hipanel:domain', "Public key")])->label(false) ?>
                        </div>

                        <div class="col-sm-2">
                            <?php if ($model->isNewRecord) : ?>
                                <div class="btn-group">
                                    <button type="button" class="add-item btn btn-success btn-sm"><i
                                            class="glyphicon glyphicon-plus"></i></button>
                                    <button type="button" class="remove-item btn btn-danger btn-sm"><i
                                            class="glyphicon glyphicon-minus"></i></button>
                                </div>
                            <?php endif ?>
                        </div>
                    </div>
                    <?php Box::end() ?>
                </div>
            <?php endforeach; ?>
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
