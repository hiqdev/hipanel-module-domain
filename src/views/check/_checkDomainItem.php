<?php

use hipanel\modules\domain\forms\CheckForm;
use hipanel\modules\domain\models\Domain;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var CheckForm $model */
$addToCartPath = '/domain/domain/add-to-cart-registration';

$canBuyDomain = Yii::$app->user->isGuest || Yii::$app->user->can('domain.pay');

$isAvailableCssClass = $model->isAvailable ? 'available' : 'unavailable';
$isSuggestionCssClass = $model->isSuggestion ? 'suggestion' : '';
$isotopeFilterCssClass = Domain::setIsotopeFilterValue($model->zone);

?>

<div class="domain-iso-line <?= $isAvailableCssClass ?> <?= $isotopeFilterCssClass ?> <?= $isSuggestionCssClass ?>">
    <div
        class="domain-line <?= $model->isAvailable ? 'checked' : '' ?>"
        data-domain="<?= $model->fqdn ?>">
        <div class="col-md-5 col-sm-12 col-xs-12">
            <?php if (isset($model->isAvailable)) : ?>
                <span class="domain-img"><i class="fa fa-globe fa-lg"></i></span>
            <?php else : ?>
                <span class="domain-img"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i></span>
            <?php endif; ?>

            <?php if ($model->isAvailable) : ?>
                <span class="domain-name"><?= $model->getDomain() ?></span><span
                    class="domain-zone">.<?= $model->getZone() ?></span>
            <?php else : ?>
                <span class="domain-name muted"><?= $model->getDomain() ?></span><span
                    class="domain-zone muted">.<?= $model->getZone() ?></span>
            <?php endif; ?>
        </div>
        <div class="col-md-4 col-sm-12 col-xs-12 text-center">
        <span class="domain-price">
            <?php if ($model->isAvailable) : ?>
                <b><?= Yii::$app->formatter->format($model->resource->price, ['currency', $model->resource->currency]) ?></b>
                <span class="domain-price-year">/ <?= Yii::t('hipanel:domain', 'year') ?></span>

            <?php elseif ($model->isAvailable === false) : ?>
                <span class="domain-taken">
                    <?= Yii::t('hipanel:domain', 'Domain is not available') ?>
                </span>
            <?php endif; ?>
        </span>
        </div>
        <div class="col-md-3 col-sm-12 col-xs-12">
            <?php if ($model->isAvailable && $canBuyDomain) : ?>
                <?= Html::a('<i class="fa fa-cart-plus fa-lg"></i>&nbsp; ' . Yii::t('hipanel:domain', 'Add to cart'), ['add-to-cart-registration', 'name' => $model->fqdn], [
                    'data-pjax' => 0,
                    'class' => 'btn btn-flat bg-olive add-to-cart-button',
                    'data-loading-text' => '<i class="fa fa-circle-o-notch fa-spin fa-lg"></i>&nbsp;&nbsp;' . Yii::t('hipanel:domain', 'Adding'),
                    'data-complete-text' => '<i class="fa fa-check fa-lg"></i>&nbsp;&nbsp;' . Yii::t('hipanel:domain', 'In cart'),
                    'data-domain-url' => Url::to([$addToCartPath, 'name' => $model->fqdn]),
                ]) ?>
            <?php elseif ($model->isAvailable === false) : ?>
                <?= Html::a('<i class="fa fa-search"></i>&nbsp; ' . Yii::t('hipanel:domain', 'WHOIS'), ['/domain/whois/index', 'domain' => $model->fqdn], ['target' => '_blank', 'class' => 'btn btn-default btn-flat']) ?>
            <?php endif; ?>
        </div>
    </div>
</div>
