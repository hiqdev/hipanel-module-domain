<?php

use hipanel\modules\domain\widgets\UsePremiumFeaturesButton;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<?php $form = ActiveForm::begin([
    'id' => 'park-form-' . ($model->id ?: time()),
    'action' => '@dns/record/' . $model->scenario,
    'enableAjaxValidation' => true,
    'options' => [
        'data-pjax' => true,
        'data-pjaxPush' => false,
    ],
    'validationUrl' => Url::toRoute(['@domain/validate-park-form', 'scenario' => $model->scenario]),
]) ?>

<?php if (!$model->isNewRecord) : ?>
    <?= Html::activeHiddenInput($model, "id") ?>
<?php endif; ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'title') ?>
            <?= $form->field($model, 'siteheader') ?>
            <?= $form->field($model, 'type_id')->dropDownList([], ['prompt' => Yii::t('hipanel:domain', 'Turn off')]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'sitetext')->textarea(['rows' => 7]) ?>
        </div>
        <div class="col-md-12">
            <?php if ((bool)$domain->is_premium === false) : ?>
                <?= UsePremiumFeaturesButton::widget([
                    'text' => Yii::t('hipanel:domain', 'Save'),
                    'options' => ['class' => 'btn btn-success btn-sm'],
                ]) ?>
            <?php else : ?>
                <?= Html::submitButton(Yii::t('hipanel:domain', 'Save'), ['class' => 'btn btn-success btn-sm']) ?>
            <?php endif; ?>
        </div>
    </div>
<?php $form->end();
