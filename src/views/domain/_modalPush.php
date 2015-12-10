<?php

use hipanel\helpers\Url;
use hipanel\modules\client\widgets\combo\ClientCombo;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>

<?php $form = ActiveForm::begin([
    'id' => 'push-domain-form',
    'action' => Url::toRoute('push'),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => $hasPincode['pincode_enabled'] ? 'push-with-pincode' : 'push']),
]) ?>

    <div class="alert alert-info alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
        </button>

        <h4><i class="fa fa-info-circle"></i>&nbsp;&nbsp;<?= Yii::t('app', 'Notice') ?></h4>

        <p>
            <?= Yii::t('app', 'The operation to transfer the domain to another user irrevocably. You can not bring it back.'); ?>
            <?= Yii::t('app', 'To confirm this, you need to enter a PIN code.'); ?>
        </p>
    </div>

<?= Html::activeHiddenInput($model, "[$model->id]id") ?>
<?= Html::activeHiddenInput($model, "[$model->id]domain") ?>
<?= Html::activeHiddenInput($model, "[$model->id]sender", ['value' => $model->client]) ?>

<?= $form->field($model, "[$model->id]receiver")->widget(ClientCombo::className()) ?>

<?php if ($hasPincode['pincode_enabled']) : ?>
    <?= $form->field($model, "[$model->id]pincode") ?>
<?php endif; ?>

<hr>
<?= Html::submitButton('Send', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end() ?>