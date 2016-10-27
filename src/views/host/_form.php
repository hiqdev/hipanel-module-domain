<?php

use hipanel\widgets\Box;
use hiqdev\combo\StaticCombo;
use hipanel\widgets\DynamicFormWidget;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

?>

<div class="row">
    <div class="col-md-3">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t('hipanel/domain', 'Что такое NS-сервер и как он работает') ?></h3>
            </div>
            <div class="box-body text-justify">
                <p>
                    В интернете каждый компьютер или сервер имеет свой IP-адрес и все обращения к тому или иному серверу
                    ведутся именно по IP-адресам. Однако, IP-адреса сложно запомнить, поэтому была придумана система
                    доменных имен, когда вы обращаетесь к серверу по легко запоминаемому доменному имени.
                </p>
                <h3>Как это работает система DNS?</h3>
                <p>Когда вы набираете в браузере доменное имя <b>mydomain.com</b>, ваш компьютер для первым делом
                    обращается к DNS-серверу, указанному в настройках вашего соединения с интернетом. DNS-сервер нужен
                    для того, чтобы преобразовать запрошенное доменное имя в IP-адрес.
                    DNS-сервер обращается к одному из корневых NS-серверов интернета, ip-адреса которых жестко заданы и
                    известны и в ответ Корневой сервер отдает DNS-серверу список ip-адресов серверов, на которых
                    расположена зона <b>.com</b>
                </p>

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
                                        return "' . Yii::t('hipanel/domain', 'Up to 13 IPv4 or IPv6 addresses separated with comma') . '";
                                    }
                                ')
                                    ],
                                ],
                            ])->label(false)->hint(Yii::t('hipanel/domain', 'Up to 13 IPv4 or IPv6 addresses separated with comma')) ?>
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
