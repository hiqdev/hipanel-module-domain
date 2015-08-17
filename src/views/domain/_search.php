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
        <?= $form->field('domain_like') ?>
        <?= $form->field('note') ?>
    </div>

    <div class="col-md-4">
        <?= $form->field('client_id')->widget(ClientCombo::classname()) ?>
        <?= $form->field('seller_id')->widget(ClientCombo::classname()) ?>
    </div>

    <div class="col-md-4">
        <?= $form->field('state')->widget(StaticCombo::classname(), [
            'data' => $stateData,
            'hasId' => true,
            'pluginOptions' => [
                'select2Options' => [
                    'multiple' => false,
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
<?php $form::end() ?>
