<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>

<?php $form = ActiveForm::begin() ?>

<div class="row margin-bottom">
    <div class="col-xs-5">
        <?= Html::tag('b', 'Name server') ?>
    </div>
    <div class="col-xs-5">
        <?= Html::tag('b', 'IP addresses (up to 13 items, IPv4 or IPv6) comma or space delimited') ?>
    </div>
    <div class="col-xs-2">

    </div>
</div>

<div class="row input-row">
    <div class="col-xs-5">
        <?= $form->field($model, 'host[]')->textInput([])->label(false); ?>
    </div>
    <div class="col-xs-5">
        <?= $form->field($model, 'ips[]')->textInput([])->label(false); ?>
    </div>
    <div class="col-xs-2">
        <?= Html::button('<i class="fa fa-plus"></i>', ['class' => 'btn btn-default btn-flat btn-add']) ?>
        <?= Html::button('<i class="fa fa-minus"></i>', ['class' => 'btn btn-default btn-flat btn-remove']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

