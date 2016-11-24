<?php

use hipanel\helpers\Url;
use hipanel\modules\domain\models\Domain;
use hipanel\widgets\ArraySpoiler;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var Domain $model
 * @var Domain[] $models
 * @var bool $hasPincode
 */
$unPushable = [];
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

    <h4><i class="fa fa-info-circle"></i>&nbsp;&nbsp;<?= Yii::t('hipanel', 'Notice') ?></h4>

    <p>
        <?= Yii::t('hipanel:domain', 'This operation pushes the domain to another user irrevocably. You can not bring it back.') ?>
        <?php if ($hasPincode) : ?>
            <?= Yii::t('hipanel', 'To confirm this operation please enter your PIN code') ?>
        <?php endif; ?>
    </p>
</div>

<div class="panel panel-info">
    <div class="panel-heading"><?= Yii::t('hipanel:domain', 'Affected domains') ?></div>
    <div class="panel-body">
        <?= ArraySpoiler::widget([
            'data' => $models,
            'visibleCount' => count($models),
            'formatter' => function ($model) use (&$unPushable) {
                if (!$model->isPushable()) {
                    $unPushable[] =  $model->domain;
                }
                return $model->domain;
            },
            'delimiter' => ',&nbsp; ',
        ]); ?>
    </div>
</div>
<?php if (!empty($unPushable)) : ?>
    <div class="panel panel-warning">
        <div class="panel-heading">
            <?= Yii::t('hipanel:domain', 'Selected domains contain items which can not be Push:') ?>
        </div>
        <div class="panel-body">
            <?= implode(', ', $unPushable) ?>
        </div>
    </div>
<?php endif; ?>

<?php foreach ($models as $model) : ?>
    <?php if ($model->isPushable()) : ?>
        <?= Html::activeHiddenInput($model, "[$model->id]id") ?>
        <?= Html::activeHiddenInput($model, "[$model->id]domain") ?>
        <?= Html::activeHiddenInput($model, "[$model->id]sender", ['value' => $model->client]) ?>
    <?php endif; ?>
<?php endforeach; ?>

<?= $form->field($model, 'receiver')->textInput(['autocomplete' => 'off']) ?>

<?php if ($hasPincode) : ?>
    <?= $form->field($model, 'pincode')->input('password', ['autocomplete' => 'off']) ?>
<?php endif; ?>
<hr>
<?= Html::submitButton(Yii::t('hipanel:domain', 'Push'), ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end() ?>
