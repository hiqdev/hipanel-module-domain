<?php

use hipanel\helpers\StringHelper;
use hipanel\modules\domain\assets\NSyncPluginAsset;
use hipanel\widgets\Pjax;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

Yii::$app->assetManager->forceCopy = true;
NSyncPluginAsset::register($this);
?>

<?php Pjax::begin(['id' => 'nss-pjax-container', 'enablePushState' => false, 'enableReplaceState' => true]) ?>
<?php $this->registerJs("
$('#nss-form-pjax').NSync();

$(document).on('pjax:send', function(event) {
    event.preventDefault()
    $('#nss-save-button').button('loading');

});
$(document).on('pjax:complete', function(event) {
  event.preventDefault()
  $('#nss-save-button').button('reset')
});
"); ?>
<?php $form = ActiveForm::begin([
    'id' => 'nss-form-pjax',
    'action' => 'set-nss',
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-nss', 'scenario' => 'default']),
    'options' => [
        'data-pjax' => true,
        'data-pjaxPush' => false,
    ],
]); ?>
<?= Html::activeHiddenInput($model, "id") ?>
<?= Html::activeHiddenInput($model, "domain") ?>

    <div class="row" style="margin-top: 15pt;">
        <div class="col-md-10 inline-form-selector">
            <?= Html::activeTextInput($model, 'nsips', [
                'class' => 'form-control',
                'placeholder' => $model->getAttributeLabel('nameservers'),
                'autocomplete' => 'off',
            ]) ?>
        </div>
        <div class="col-md-2 text-right">
            <?= Html::submitButton(Yii::t('app', 'Save'), [
                'class' => 'btn btn-default',
                'id' => 'nss-save-button',
                'data-loading-text' => Yii::t('app', 'Saving') . '...',
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <hr>
            <div class="">
                <div class="">
                    <?php DynamicFormWidget::begin([
                        'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                        'widgetBody' => '.container-items', // required: css class selector
                        'widgetItem' => '.item', // required: css class
                        'limit' => 13, // the maximum times, an element can be cloned (default 999)
                        'min' => 1, // 0 or 1 (default 1)
                        'insertButton' => '.add-item', // css class
                        'deleteButton' => '.remove-item', // css class
                        'model' => reset($nsModels),
                        'formId' => 'nss-form-pjax',
                        'formFields' => [
                            'name',
                            'ip',
                            'domain_name',
                        ],
                    ]) ?>

                    <div class="container-items">
                        <?php foreach ($nsModels as $i => $nsModel): ?>
                            <?= Html::activeHiddenInput($nsModel, "[$i]domain_name", ['value' => $model->domain, 'class' => 'domain_name']) ?>
                            <div class="item">
                                <div class="row" style="margin-bottom: 5pt">
                                    <div class="col-md-5">
                                        <?= $form->field($nsModel, "[$i]name")->textInput(['placeholder' => $nsModel->getAttributeLabel('name')])->label(false) ?>
                                    </div>
                                    <div class="col-md-5">
                                        <?= $form->field($nsModel, "[$i]ip")->textInput(['disabled' => !StringHelper::endsWith($nsModel->name, $model->domain), 'placeholder' => $nsModel->getAttributeLabel('ip')])->label(false) ?>
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="add-item btn btn-default"><i
                                                    class="glyphicon glyphicon-plus"></i></button>
                                            <button type="button" class="remove-item btn btn-default"><i
                                                    class="glyphicon glyphicon-minus"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <?= Html::activeHiddenInput($nsModel, "[$i]domain_name", ['value' => $model->domain]) ?>
                            </div>

                        <?php endforeach; ?>
                    </div>
                    <?php DynamicFormWidget::end(); ?>
                </div>
            </div>
        </div>
    </div>

<?php ActiveForm::end(); ?>
<?php Pjax::end(); ?>