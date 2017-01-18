<?php

use hipanel\widgets\Box;
use hipanel\widgets\DynamicFormWidget;
use hiqdev\combo\StaticCombo;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

?>

<div class="row">
    <div class="col-md-3">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t('hipanel:domain', 'Child name server or glue record') ?></h3>
            </div>
            <div class="box-body text-justify">
                <p>
                    <?= Yii::t('hipanel:domain', 'You can assign up to 13 IPv4 and/or 13 IPv6 addresses to name server.') ?>
                </p>
                <p>
                    <?= Yii::t('hipanel:domain', 'Child Name Servers can be only created by owner of the primary domain name.') ?>
                    <?= Yii::t('hipanel:domain', 'That is you can only create ns1.my-domain.com if you are owner of my-domain.com.') ?>
                </p>
                <h3><?= Yii::t('hipanel:domain', 'Examples') ?></h3>

                <p>ns1.my-domain.com  10.10.10.10</p>
                <p>ns2.my-domain.com  12.12.12.12</p>

            </div>
        </div>
    </div>
    <div class="col-md-9">
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
                'ips',
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
                            <?php if ($model->isNewRecord) : ?>
                                <?= $form->field($model, "[$i]host")->textInput(['placeholder' => Yii::t('hipanel', 'Name server')])->label(false) ?>
                            <?php else : ?>
                                <p class="form-control-static text-center"><?= $model->host; ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="col-sm-5">
                            <?= $form->field($model, "[$i]ips")->widget(StaticCombo::class, [
                                'formElementSelector' => '.item',
                                'inputOptions' => [
                                    'placeholder' => Yii::t('hipanel', 'IP addresses'),
                                    'value' => is_array($model->ips) ? implode(',', $model->ips) : $model->ips,
                                ],
                                'pluginOptions' => [
                                    'select2Options' => [
                                        'tags' => [],
                                        'multiple' => true,
                                        'tokenSeparator' => [';', ',', ' '],
                                        'minimumResultsForSearch' => -1,
                                        'createSearchChoice' => new JsExpression('
                                    function(term, data) {
                                        return {id:term, text:term};
                                    }
                                '),
                                        'formatNoMatches' => new JsExpression('
                                    function (term) {
                                        return "' . Yii::t('hipanel:domain', 'Up to 13 IPv4 or IPv6 addresses separated with comma') . '";
                                    }
                                '),
                                    ],
                                ],
                            ])->label(false)->hint(Yii::t('hipanel:domain', 'Up to 13 IPv4 or IPv6 addresses separated with comma')) ?>
                        </div>
                        <div class="col-sm-2">
                            <?php if ($model->isNewRecord) : ?>
                                <div class="btn-group">
                                    <button type="button" class="add-item btn btn-success btn-sm"><i
                                            class="glyphicon glyphicon-plus"></i></button>
                                    <button type="button" class="remove-item btn btn-danger btn-sm"><i
                                            class="glyphicon glyphicon-minus"></i></button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php Box::end() ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php DynamicFormWidget::end() ?>
        <?php Box::begin(['options' => ['class' => 'box-solid']]) ?>
        <div class="row">
            <div class="col-md-12 no">
                <?= Html::submitButton(Yii::t('hipanel', 'Save'), ['class' => 'btn btn-success']) ?>
                &nbsp;
                <?= Html::button(Yii::t('hipanel', 'Cancel'), ['class' => 'btn btn-default', 'onclick' => 'history.go(-1)']) ?>
            </div>
        </div>
        <?php Box::end() ?>
        <?php ActiveForm::end() ?>

    </div>
</div>
