<?php

use hipanel\modules\client\widgets\combo\ClientCombo;
use hiqdev\combo\StaticCombo;
use kartik\widgets\DatePicker;
use yii\helpers\Html;

?>

    <div class="col-md-4">
        <?= $search->field('domain_like') ?>
        <?= $search->field('note') ?>
    </div>

    <div class="col-md-4">
        <?= $search->field('client_id')->widget(ClientCombo::classname()) ?>
        <?= $search->field('seller_id')->widget(ClientCombo::classname()) ?>
    </div>

    <div class="col-md-4">
        <?= $search->field('state')->widget(StaticCombo::classname(), [
            'data' => $stateData,
            'hasId' => true,
            'pluginOptions' => [
                'select2Options' => [
                    'multiple' => false,
                ],
            ],
        ]) ?>
        <div class="form-group">
            <?= Html::tag('label', 'Registered range', ['class' => 'control-label']); ?>
            <?= DatePicker::widget([
                'model'         => $search->model,
                'type'          => DatePicker::TYPE_RANGE,
                'attribute'     => 'created_from',
                'attribute2'    => 'created_till',
                'pluginOptions' => [
                    'autoclose' => true,
                    'format'    => 'dd-mm-yyyy',
                ],
            ]) ?>
        </div>
    </div>
