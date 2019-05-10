<?php

use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\modules\client\widgets\combo\SellerCombo;
use hipanel\modules\domain\models\Domain;
use hiqdev\combo\StaticCombo;
use hiqdev\yii2\daterangepicker\DateRangePicker;
use yii\helpers\Html;

/**
 * @var \hipanel\widgets\AdvancedSearch $search
 * @var \yii\web\View $this
 */
?>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('domain_like') ?>
</div>
<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('domains')->textarea() ?>
</div>
<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('note') ?>
</div>
<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('emails')->textarea() ?>
</div>

<?php if (Yii::$app->user->can('support')) {
    ?>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('client_id')->widget(ClientCombo::class) ?>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('seller_id')->widget(SellerCombo::class) ?>
    </div>
<?php
} ?>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('state')->widget(StaticCombo::class, [
        'attribute' => 'state',
        'model' => $search->model,
        'hasId' => true,
        'data' => Domain::stateOptions(),
    ]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <div class="form-group">
        <?= Html::tag('label', Yii::t('hipanel:domain', 'Registered range'), ['class' => 'control-label']); ?>
        <?= DateRangePicker::widget([
            'model' => $search->model,
            'attribute' => 'created_from',
            'attribute2' => 'created_till',
            'options' => [
                'class' => 'form-control',
            ],
            'dateFormat' => 'yyyy-MM-dd',
        ]) ?>
    </div>
</div>
