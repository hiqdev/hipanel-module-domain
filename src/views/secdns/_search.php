<?php

use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\modules\client\widgets\combo\SellerCombo;
use hipanel\modules\domain\models\Secdns;
use hiqdev\combo\StaticCombo;
use yii\helpers\Html;

/**
 * @var \hipanel\widgets\AdvancedSearch $search
 * @var \yii\web\View $this
 */
?>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('domain_like') ?>
</div>
<?php if (Yii::$app->user->can('client.read-all')) {
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
    <?= $search->field('key_tag') ?>
</div>
<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('digest_alg')->widget(StaticCombo::class, [
        'attribute' => 'digest_alg',
        'model' => $search->model,
        'hasId' => true,
        'data' => Secdns::algorithmTypesWithLabels(),
    ]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('digest_type')->widget(StaticCombo::class, [
        'attribute' => 'digest_type',
        'model' => $search->model,
        'hasId' => true,
        'data' => Secdns::digestTypesWithLabels(),
    ]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('key_alg')->widget(StaticCombo::class, [
        'attribute' => 'key_alg',
        'model' => $search->model,
        'hasId' => true,
        'data' => Secdns::algorithmTypesWithLabels(),
    ]) ?>
</div>


