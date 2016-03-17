<?php

use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\modules\domain\models\Domain;
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
        <?= $search->field('state')->dropDownList(Domain::stateOptions(), ['prompt' => '--']) ?>
        <div class="form-group">
            <?= Html::tag('label', Yii::t('hipanel/domain', 'Registered range'), ['class' => 'control-label']); ?>
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
