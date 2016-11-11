<?php

/** @var string $sShotSrc */
/** @var \hipanel\modules\domain\models\Domain $model */

use hipanel\modules\dashboard\widgets\SmallBox;
use hipanel\modules\domain\widgets\WhoisData;
use hipanel\widgets\ArraySpoiler;
use toriphes\lazyload\LazyLoad;
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->registerCss("
.shot-img {
    background-color: #f4f4f4;
    width: 520px;
    height: 325px;
}
");
?>

<?php if ($model->registrar) : ?>
    <div class="row">
        <div class="col-md-4">
            <div class="md-mb-10 text-center">
                <?= LazyLoad::widget([
                    'src' => $model->screenshot,
                    'options' => [
                        'class' => 'img-thumbnail shot-img',
                        'alt' => $model->domain,
                    ]
                ]) ?>
            </div>
        </div>
        <div class="col-md-8">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'domain',
                    [
                        'attribute' => 'created',
                        'format' => 'date',
                        'visible' => !empty($model->created),
                    ],
                    [
                        'attribute' => 'updated',
                        'format' => 'date',
                        'visible' => !empty($model->updated),
                    ],
                    [
                        'attribute' => 'expires',
                        'format' => 'date',
                        'visible' => !empty($model->expires),
                    ],
                    [
                        'attribute' => 'registrar',
                        'value' => is_array($model->registrar) ? rtrim(implode(', ', $model->registrar), ', ') : $model->registrar,
                        'visible' => !empty($model->expires),
                    ],
                    [
                        'attribute' => 'nss',
                        'value' => ArraySpoiler::widget([
                            'data' => $model->nss,
                            'visibleCount' => count($model->nss),
                            'formatter' => function ($ip, $ns) {
                                return $ns . ' - ' . $ip;
                            },
                            'delimiter' => '<br>',
                        ]),
                        'format' => 'html',
                    ],
                    [
                        'attribute' => 'ip',
                    ],
                    [
                        'attribute' => 'country_name',
                    ],
                    [
                        'attribute' => 'city',
                        'visible' => !empty($model->city),
                    ],
                ]
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" style="font-family: monospace">
            <div class="well well-sm"><?= WhoisData::widget(['data' => $model->rawdata]) ?></div>
        </div>
    </div>
<?php endif ?>

<?php if ($model->available || (!$model->registrar && !$model->unsupported)) : ?>
    <table class="table">
        <thead>
        <tr class="success">
            <td class="text-bold" style="vertical-align: middle;">
                <?= Yii::t('hipanel/domain', 'This domain is available for registration') ?>
            </td>
            <td>
                <?= Html::a('<i class="fa fa-fw fa-cart-plus"></i> ' . Yii::t('hipanel/domain', 'Buy domain'),
                    ['@domain/add-to-cart-registration', 'name' => $model->domain],
                    ['class' => 'btn btn-flat btn-block btn-sm ' . SmallBox::COLOR_OLIVE]
                ) ?>
            </td>
        </tr>
        </thead>
    </table>
<?php endif ?>

<?php if ($model->unsupported) : ?>
    <table class="table">
        <thead>
        <tr class="danger">
            <td class="text-center text-bold">
                <?= Yii::t('hipanel/domain', 'You have entered wrong domain name or domain name with unsupported zone.') ?>
            </td>
        </tr>
        </thead>
    </table>
<?php endif ?>
