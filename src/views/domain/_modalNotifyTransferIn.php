<?php

use hipanel\helpers\Url;
use hipanel\modules\domain\models\Domain;
use hipanel\widgets\ArraySpoiler;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var Domain
 * @var Domain[] $models
 */

$this->registerCss("
    .domain-send-to-radio label {
        display: block;
    }
");

$this->registerJs(<<<JS
    $('.domain-send-to-radio input[name="send_to"]').on('change', function() {
        if ($(this).val() === 'force_email') {
            $('#domain-force-email').removeAttr('disabled', 'disabled');
        } else {
            $('#domain-force-email').attr('disabled', 'disabled');
        }
    });
JS
);

$unNotifiedTransferIn = [];
?>

<?php $form = ActiveForm::begin([
    'id' => 'force-notify-transfer-in-form',
    'action' => Url::toRoute('@domain/force-notify-transfer-in'),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-force-notify-transfer-in', 'scenario' => 'force-notify-transfer-in']),
]) ?>

<div class="panel panel-info">
    <div class="panel-heading"><?= Yii::t('hipanel:domain', 'Affected domains') ?></div>
    <div class="panel-body">
        <?= ArraySpoiler::widget([
            'data' => $models,
            'visibleCount' => count($models),
            'formatter' => function ($model) use (&$unNotifiedTransferIn) {
                if (!$model->canSendFOA()) {
                    $unNotifiedTransferIn[] =  $model->domain;
                }
                return $model->domain;
            },
            'delimiter' => ',&nbsp; ',
        ]); ?>
    </div>
</div>
<?php if (!empty($unNotifiedTransferIn)) : ?>
    <div class="panel panel-warning">
        <div class="panel-heading">
            <?= Yii::t('hipanel:domain', 'Selected domains are not waiting transfer confirmation') ?>
        </div>
        <div class="panel-body">
            <?= implode(', ', $unNotifiedTransferIn) ?>
        </div>
    </div>
<?php endif; ?>


<?php foreach ($models as $model) : ?>
    <?php if ($model->canSendFOA()) : ?>
        <?= Html::activeHiddenInput($model, "[$model->id]id") ?>
        <?= Html::activeHiddenInput($model, "[$model->id]domain") ?>
    <?php endif ?>
<?php endforeach ?>

<?= Html::radioList('send_to', 'whois', [
    Domain::SEND_TO_REGISTRANT_EMAIL => Yii::t('hipanel:domain', 'Send FOA to registant\'s email'),
    Domain::SEND_TO_CLIENT_EMAIL => Yii::t('hipanel:domain', 'Send FOA to client\'s email'),
    Domain::SEND_TO_WHOIS_EMAIL => Yii::t('hipanel:domain', 'Send FOA to WHOIS email'),
    Domain::SEND_TO_FORCE_EMAIL => Yii::t('hipanel:domain', 'Send FOA to specific email'),
], ['class' => 'domain-send-to-radio']) ?>

<?= $form->field($model, 'force_email')->textInput([
    'class' => 'form-control',
    'placeholder' => reset($models)->getAttributeLabel('force_email'),
    'autocomplete' => 'off',
    'id' => 'domain-force-email',
    'disabled' => 'disabled',
])->label(false) ?>

<hr>
<?= Html::submitButton(Yii::t('hipanel:domain', 'Send FOA'), ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end() ?>
