<?php

use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\widgets\AdvancedSearch;
use hiqdev\combo\StaticCombo;
use kartik\widgets\DatePicker;
use yii\helpers\Url;
use yii\helpers\Html;

?>

<?php $form = AdvancedSearch::begin(compact('model')) ?>

    <div class="col-md-4">
        <?= $form->field('domain') ?>
        <?= $form->field('note') ?>
    </div>

    <div class="col-md-4">
        <?= $form->field('client_id')->widget(ClientCombo::classname()) ?>
        <?= $form->field('seller_id')->widget(ClientCombo::classname()) ?>
    </div>

    <div class="col-md-4">
        <?= $form->field('state')->widget(StaticCombo::classname(), [
            'data' => $state_data,
            'hasId' => true,
            'pluginOptions' => [
                'select2Options' => [
                    'multiple' => true,
                ]
            ],
        ]) ?>
        <div class="form-group">
            <?= Html::tag('label', 'Registered range', ['class' => 'control-label']); ?>
            <?= DatePicker::widget([
                'model'         => $model,
                'type'          => DatePicker::TYPE_RANGE,
                'attribute'     => 'created_from',
                'attribute2'    => 'created_till',
                'pluginOptions' => [
                    'autoclose' => true,
                    'format'    => 'dd-mm-yyyy'
                ]
            ]) ?>
        </div>
    </div>

    <div class="col-md-12">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
    </div>

<?php $form::end() ?>
