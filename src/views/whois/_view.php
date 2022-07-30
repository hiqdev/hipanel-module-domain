<?php

/** @var string $sShotSrc */
/** @var \hipanel\modules\domain\models\Domain $model */
use hipanel\modules\dashboard\widgets\SmallBox;
use hipanel\modules\domain\models\Whois;
use hipanel\modules\domain\widgets\WhoisData;
use hipanel\widgets\ArraySpoiler;
use toriphes\lazyload\LazyLoad;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

$this->registerCss('
.shot-img {
    background-color: #f4f4f4;
    width: 520px;
    height: 325px;
}
');

?>

<?php switch ($model->availability) : ?>
<?php case Whois::REGISTRATION_UNAVAILABLE: ?>
        <div class="row">
            <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="md-mb-10 text-center">
                    <?= LazyLoad::widget([
                        'src' => $model->screenshot,
                        'options' => [
                            'style' => 'width: 380px',
                            'alt' => $model->domain,
                        ],
                    ]) ?>
                </div>
            </div>
            <div class="col-md-8 col-sm-12 col-xs-12">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        [
                            'attribute' => 'domain',
                            'value' => $model->getDomainAsUtf8(),
                        ],
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
                                'data' => array_map(fn ($ip, $ns) => $ns . ' - ' . $ip, $model->nss ?? '', array_keys($model->nss ?? [])),
                                'visibleCount' => count($model->nss),
                                'delimiter' => '<br>',
                            ]),
                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'seo',
                            'label' => Yii::t('hipanel:domain', 'SEO'),
                            'value' => ArraySpoiler::widget([
                                'data' => ['google', 'alexa', 'yandex'],
                                'visibleCount' => 3,
                                'delimiter' => '<br>',
                                'formatter' => function ($attribute) use ($model) {
                                    if ($model->{$attribute}) {
                                        return $model->getAttributeLabel($attribute) . ': ' . $model->{$attribute};
                                    }
                                },
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
                    ],
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" style="font-family: monospace">
                <div class="well well-sm"><?= WhoisData::widget(['data' => $model->rawdata]) ?></div>
            </div>
        </div>
        <?php break; ?>

    <?php case Whois::REGISTRATION_AVAILABLE: ?>
        <table class="table">
            <thead>
            <tr class="success">
                <td class="text-bold" style="vertical-align: middle;">
                    <?= Yii::t('hipanel:domain', 'This domain is available for registration') ?>
                </td>
                <td>
                    <?php if (Yii::$app->user->can('domain.pay')) : ?>
                        <?= Html::a('<i class="fa fa-fw fa-cart-plus"></i> ' . Yii::t('hipanel:domain', 'Register domain'),
                            ['/cart/cart/index'],
                            [
                                'data-pjax' => 0,
                                'data-loading-text' => '<i class="fa fa-circle-o-notch fa-spin fa-lg"></i>&nbsp;&nbsp;' . Yii::t('hipanel:domain', 'Adding'),
                                'data-complete-text' => '<i class="fa fa-check fa-lg"></i>&nbsp;&nbsp;' . Yii::t('hipanel:domain', 'Go to the cart'),
                                'data-domain-url' => Url::to(['@domain/add-to-cart-registration', 'name' => $model->domain]),
                                'class' => 'btn btn-flat add-to-cart-button btn-block btn-sm ' . SmallBox::COLOR_OLIVE,
                            ]
                        ) ?>
                    <?php endif ?>
                </td>
            </tr>
            </thead>
        </table>
        <?php break; ?>
    <?php case WHOIS::REGISTRATION_PREMIUM: ?>
    <?php case Whois::REGISTRATION_UNSUPPORTED: ?>
        <table class="table">
            <thead>
            <tr class="danger">
                <td class="text-center text-bold">
                    <?php if ($model->availability === WHOIS::REGISTRATION_PREMIUM) : ?>
                        <?= Yii::t('hipanel:domain', 'Domain name is not registered yet, or domain name is premium, or the domain zone is not supported.') ?>
                    <?php else : ?>
                        <?= Yii::t('hipanel:domain', 'Domain name is not registered yet, or the domain zone is not supported.') ?>
                    <?php endif ?>
                </td>
            </tr>
            </thead>
        </table>
        <?php break; ?>
    <?php endswitch; ?>
