<?php

use hipanel\modules\domain\widgets\UsePremiumFeaturesButton;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<?php $form = ActiveForm::begin([
    'id' => 'mailfw-form-' . ($model->id ?: time()),
    'action' => '@dns/record/' . $model->scenario,
    'enableAjaxValidation' => true,
    'options' => [
        'data-pjax' => true,
        'data-pjaxPush' => false,
    ],
    'validationUrl' => Url::toRoute(['@domain/validate-mailfw-form', 'scenario' => $model->scenario]),
]) ?>

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
            <?php if ((bool)$domain->is_premium === false) : ?>
                <?= UsePremiumFeaturesButton::widget([
                    'text' => Yii::t('hipanel:domain', 'Add record'),
                    'options' => ['class' => 'btn btn-success btn-sm'],
                ]) ?>
            <?php else : ?>
                <?= Html::submitButton(Yii::t('hipanel:domain', 'Add record'), ['class' => 'btn btn-success btn-sm']) ?>
            <?php endif; ?>
        </div>
    </div>
<?php $form->end();
