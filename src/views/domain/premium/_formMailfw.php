<?php

use hipanel\modules\domain\widgets\UsePremiumFeaturesButton;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$model->domain_id = $domain->id;

?>

<?php $form = ActiveForm::begin([
    'id' => 'mailfw-form-' . ($model->id ?: time()),
    'action' => Url::to(['@domain/set-premium-feature', 'for' => 'mailfw']),
    'enableAjaxValidation' => true,
    'options' => [
        'data-pjax' => true,
        'data-pjaxPush' => false,
    ],
    'validationUrl' => Url::toRoute(['@domain/validate-mailfw-form', 'scenario' => $model->scenario]),
]) ?>

<?= Html::activeHiddenInput($model, "domain_id") ?>
<?= Html::activeHiddenInput($model, "status") ?>

<?php if (!$model->isNewRecord) : ?>
    <?= Html::activeHiddenInput($model, "id") ?>
<?php endif; ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'name', ['template' => '{label}<div class="input-group">{input}<div class="input-group-addon">@' . $domain->domain . '</div></div>{hint}{error}']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'value') ?>
        </div>
        <div class="col-md-12">
            <?php if ((bool)$domain->premium->is_active === false) : ?>
                <?= UsePremiumFeaturesButton::widget([
                    'text' => Yii::t('hipanel.domain.premium', 'Add record'),
                    'options' => ['class' => 'btn btn-success btn-sm'],
                ]) ?>
            <?php else : ?>
                <?= Html::submitButton($model->isNewRecord ? Yii::t('hipanel.domain.premium', 'Add record') : Yii::t('hipanel.domain.premium', 'Update record'), ['class' => 'btn btn-success btn-sm']) ?>
            <?php endif; ?>
        </div>
    </div>
<?php $form->end();
