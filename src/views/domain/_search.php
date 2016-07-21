<?php

use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\modules\client\widgets\combo\SellerCombo;
use hipanel\modules\domain\models\Domain;
use hipanel\widgets\DatePicker;
use yii\helpers\Html;

/**
 * @var \hipanel\widgets\AdvancedSearch $search
 */
?>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('domain_like') ?>
</div>
<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('note') ?>
</div>

<?php if (Yii::$app->user->can('support')) { ?>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('client_id')->widget(ClientCombo::class) ?>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('seller_id')->widget(SellerCombo::class) ?>
    </div>
<?php } ?>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('state')->dropDownList(Domain::stateOptions(), ['prompt' => '--']) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <div class="form-group">
        <?= Html::tag('label', Yii::t('hipanel/domain', 'Registered range'), ['class' => 'control-label']); ?>
        <?= DatePicker::widget([
            'model' => $search->model,
            'type' => DatePicker::TYPE_RANGE,
            'attribute' => 'created_from',
            'attribute2' => 'created_till',
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd-mm-yyyy',
            ],
        ]) ?>
    </div>
</div>
