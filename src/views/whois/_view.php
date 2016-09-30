<?php

/** @var string $sShotSrc */
/** @var \hipanel\modules\domain\models\Domain $model */

use hipanel\widgets\ArraySpoiler;
use toriphes\lazyload\LazyLoad;
use yii\widgets\DetailView;

$this->registerCss("
.shot-img {
    background-color: #f4f4f4;
    width: 520px;
    height: 325px;
}
");
?>
<?php if ($model) : ?>
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
            <div class="well well-sm"><?= \hipanel\modules\domain\widgets\WhoisData::widget(['data' => $model->rawdata]) ?></div>
        </div>
    </div>
<?php else: ?>
    <div class="bg-danger text-center">
        <?= Yii::t('hipanel/domain', 'You have entered wrong domain name or domain name with unsupported zone.') ?>
    </div>
<?php endif ?>
