<?php

use hipanel\helpers\Url;
use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\widgets\ArraySpoiler;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>

<?php $form = ActiveForm::begin([
    'id' => 'push-domain-form',
    'action' => Url::toRoute('push'),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-push-form', 'scenario' => $hasPincode ? 'push-with-pincode' : 'push']),
]) ?>

    <div class="alert alert-info alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
        </button>

        <h4><i class="fa fa-info-circle"></i>&nbsp;&nbsp;<?= Yii::t('app', 'Notice') ?></h4>

        <p>
            <?= Yii::t('hipanel/domain', 'This operation pushes the domain to another user irrevocably. You can not bring it back.') ?>
            <?php if ($hasPincode) : ?>
                <?= Yii::t('app', 'To confirm this, you need to enter a PIN code.'); ?>
            <?php endif; ?>
        </p>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading"><?= Yii::t('app', 'Affected domains') ?></div>
        <div class="panel-body">
            <?= ArraySpoiler::widget([
                'data' => $models,
                'visibleCount' => count($models),
                'formatter' => function ($model) {
                    return $model->domain;
                },
                'delimiter' => ',&nbsp; '
            ]); ?>
        </div>
    </div>

    <?php foreach ($models as $model) : ?>
        <?= Html::activeHiddenInput($model, "[$model->id]id") ?>
        <?= Html::activeHiddenInput($model, "[$model->id]domain") ?>
        <?= Html::activeHiddenInput($model, "[$model->id]sender", ['value' => $model->client]) ?>
    <?php endforeach; ?>

    <?= $form->field($model, "receiver") ?>

    <?php if ($hasPincode) : ?>
        <?= $form->field($model, "pincode") ?>
    <?php endif; ?>
    <hr>
    <?= Html::submitButton('Send', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end() ?>
