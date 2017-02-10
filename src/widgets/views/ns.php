<?php

use hipanel\helpers\StringHelper;
use hipanel\modules\domain\assets\NSyncPluginAsset;
use hipanel\widgets\DynamicFormWidget;
use hipanel\widgets\Pjax;
use hiqdev\combo\StaticCombo;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;

// TODO: To delete this
NSyncPluginAsset::register($this);

/**
 * @var \yii\web\View
 * @var array|string $actionUrl url to send the form
 */
?>

<?php Pjax::begin(['id' => 'nss-pjax-container', 'enablePushState' => false, 'enableReplaceState' => false]) ?>
<?php $this->registerJs("
$('#nss-form-pjax').NSync();

$(document).on('pjax:send', function(event) {
    event.preventDefault();
    $('#nss-save-button').button('loading');

});
$(document).on('pjax:complete', function(event) {
  event.preventDefault();
  $('#nss-save-button').button('reset');
  $('.modal').modal('hide');
});
"); ?>
<?php $form = ActiveForm::begin([
    'id' => 'nss-form-pjax',
    'action' => $actionUrl,
    'enableAjaxValidation' => true,
    'enableClientValidation' => true,
    'validationUrl' => Url::toRoute(['validate-nss', 'scenario' => 'default']),
    'options' => [
        'data-pjax' => true,
        'data-pjaxPush' => false,
    ],
]); ?>
    <?php if (!is_array($model)) : ?>
        <?= Html::activeHiddenInput($model, 'id') ?>
        <?= Html::activeHiddenInput($model, 'domain') ?>
    <?php else : ?>
        <?php foreach ($model as $item) : ?>
            <?= Html::activeHiddenInput($item, "[$item->id]id") ?>
            <?= Html::activeHiddenInput($item, "[$item->id]domain") ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <div class="alert alert-info alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>

        <h4><i class="fa fa-info-circle"></i>&nbsp;&nbsp;<?= Yii::t('hipanel', 'Notice') ?></h4>

        <p>
            <?= Yii::t('hipanel:domain', 'With this form you can assign the authoritative name servers for your domain.') ?></p><p>
            <?= Yii::t('hipanel:domain', 'IP addresses can be assigned to child name servers only.') ?>
            <?= Yii::t('hipanel:domain', 'Child name servers are created or changed automatically according to specified data.') ?>
        </p>
    </div>

    <div class="row" style="margin-top: 15pt;">
        <div class="col-md-10 inline-form-selector">
            <?php if (!is_array($model)) : ?>
                <?= Html::activeTextInput($model, 'nsips', [
                    'class' => 'form-control',
                    'placeholder' => $model->getAttributeLabel('nameservers'),
                    'autocomplete' => 'off',
                ]) ?>
            <?php else : ?>
                <?= Html::textInput('nsips', '', [
                    'class' => 'form-control',
                    'placeholder' => reset($model)->getAttributeLabel('nameservers'),
                    'autocomplete' => 'off',
                ]) ?>
            <?php endif; ?>
        </div>
        <div class="col-md-2 text-right">
            <?= Html::submitButton(Yii::t('hipanel', 'Save'), [
                'class' => 'btn btn-success',
                'id' => 'nss-save-button',
                'data-loading-text' => '<i class="fa fa-circle-o-notch fa-spin"></i> ' . Yii::t('hipanel', 'saving'),
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
                                        <?= $form->field($nsModel, "[$i]name")->textInput([
                                            'placeholder' => $nsModel->getAttributeLabel('name'),
                                            'data-attribute' => 'name',
                                        ])->label(false) ?>
                                    </div>
                                    <div class="col-md-5">
                                        <?php if (!is_array($model)) : ?>
                                            <?= $form->field($nsModel, "[$i]ip")->widget(StaticCombo::class, [
                                                'formElementSelector' => '.item',
                                                'inputOptions' => [
                                                    'disabled' => !StringHelper::endsWith($nsModel->name, $model->domain),
                                                    'data-attribute' => 'ip',
                                                ],
                                                'data' => $nsModel->ip ? array_combine($nsModel->ip, $nsModel->ip) : [],
                                                'multiple' => true,
                                                'pluginOptions' => [
                                                    'select2Options' => [
                                                        'tags' => true,
                                                        'tokenSeparators' => [';', ',', ' '],
                                                        'minimumResultsForSearch' => new JsExpression('Infinity'),
                                                        'placeholder' => $nsModel->getAttributeLabel('ip'),
                                                        'language' => [
                                                            'noResults' => new JsExpression("function (params) {
                                                                return " . Json::encode(Yii::t('hipanel:domain', 'Up to 13 IPv4 or IPv6 addresses separated with comma')) . ";
                                                            }")
                                                        ]
                                                    ],
                                                ],
                                            ])->label(false) ?>
                                        <?php endif; ?>
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
