<?php

/** @var array $forwardingOptions */

use hipanel\modules\domain\widgets\UsePremiumFeaturesButton;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$model->domain_id = $domain->id;

?>

<?php $form = ActiveForm::begin([
    'id' => 'urlfw-form-' . ($model->id ?: time()),
    'action' => Url::to(['@domain/set-premium-feature', 'for' => 'urlfw']),
    'enableAjaxValidation' => true,
    'options' => [
        'data-pjax' => true,
        'data-pjaxPush' => false,
    ],
    'validationUrl' => Url::toRoute(['@domain/validate-urlfw-form', 'scenario' => $model->scenario]),
]) ?>

<?php if (!$model->isNewRecord) : ?>
    <?= Html::activeHiddenInput($model, "id") ?>
<?php endif; ?>

<?= Html::activeHiddenInput($model, "domain_id") ?>
<?= Html::activeHiddenInput($model, "status") ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'name', ['template' => '{label}<div class="input-group">{input}<div class="input-group-addon">.' . ($domain->domain) . '</div></div>{hint}{error}']) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'type')->dropDownList(ArrayHelper::map(array_filter($forwardingOptions, function ($ref) {
                return $ref->name === 'url_temporary';
            }, ARRAY_FILTER_USE_BOTH), 'name', 'label'), ['prompt' => '--']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'value') ?>
        </div>
        <div class="col-md-12">
            <?php if ((bool)$domain->premium->is_active === false) : ?>
                <?= UsePremiumFeaturesButton::widget([
                    'text' => $model->isNewRecord ? Yii::t('hipanel:domain', 'Add record') : Yii::t('hipanel:domain', 'Update record'),
                    'options' => ['class' => 'btn btn-success btn-sm'],
                ]) ?>
            <?php else : ?>
                <?= Html::submitButton($model->isNewRecord ? Yii::t('hipanel:domain', 'Add record') : Yii::t('hipanel:domain', 'Update record'), ['class' => 'btn btn-success btn-sm']) ?>
            <?php endif; ?>
        </div>
    </div>
<?php $form->end();
