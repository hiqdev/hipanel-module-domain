<?php
/**
 * @var array
 * @var $state string
 */

use hipanel\modules\domain\models\Domain;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var array $line */
$addToCartPath = '/domain/domain/add-to-cart-registration';
?>

<div class="domain-iso-line
    <?= Domain::setIsotopeFilterValue($line['zone']) ?>
    <?= $line['isAvailable'] ? 'available' : 'unavailable' ?>
    <?= $line['fqdn'] === $requestedDomain ? 'popular' : $requestedDomain ?>
">
    <div
        class="domain-line <?= $line['isAvailable'] === true ? 'checked' : '' ?>"
        data-domain="<?= $line['fqdn'] ?>">
        <div class="col-md-5 col-sm-12 col-xs-12">
            <?php if (isset($line['isAvailable'])) : ?>
                <span class="domain-img"><i class="fa fa-globe fa-lg"></i></span>
            <?php else : ?>
                <span class="domain-img"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i></span>
            <?php endif; ?>

            <?php if ($line['isAvailable'] === true) : ?>
                <span class="domain-name"><?= $line['domain'] ?></span><span
                    class="domain-zone">.<?= $line['zone'] ?></span>
            <?php else : ?>
                <span class="domain-name muted"><?= $line['domain'] ?></span><span
                    class="domain-zone muted">.<?= $line['zone'] ?></span>
            <?php endif; ?>
        </div>
        <div class="col-md-4 col-sm-12 col-xs-12 text-center">
        <span class="domain-price">
            <?php if ($line['isAvailable']) : ?>
                <!--del>0.00 €</del-->
                <b><?= Yii::$app->formatter->format($line['resource']->price, ['currency', $line['resource']->currency]) ?></b>
                <span class="domain-price-year">/ <?= Yii::t('hipanel:domain', 'year') ?></span>

            <?php elseif ($line['isAvailable'] === false) : ?>
                <span class="domain-taken">
                    <?= Yii::t('hipanel:domain', 'Domain is not available') ?>
                </span>
            <?php endif; ?>
        </span>
        </div>
        <div class="col-md-3 col-sm-12 col-xs-12">
            <?php if ($line['isAvailable'] === true) : ?>
                <?= Html::a('<i class="fa fa-cart-plus fa-lg"></i>&nbsp; ' . Yii::t('hipanel:domain', 'Add to cart'), ['add-to-cart-registration', 'name' => $line['fqdn']], [
                    'data-pjax' => 0,
                    'class' => 'btn btn-flat bg-olive add-to-cart-button',
                    'data-loading-text' => '<i class="fa fa-circle-o-notch fa-spin fa-lg"></i>&nbsp;&nbsp;' . Yii::t('hipanel:domain', 'Adding'),
                    'data-complete-text' => '<i class="fa fa-check fa-lg"></i>&nbsp;&nbsp;' . Yii::t('hipanel:domain', 'In cart'),
                    'data-domain-url' => Url::to([$addToCartPath, 'name' => $line['fqdn']]),
                ]) ?>
            <?php elseif ($line['isAvailable'] === false) : ?>
                <?= Html::a('<i class="fa fa-search"></i>&nbsp; ' . Yii::t('hipanel:domain', 'WHOIS'), ['/domain/whois/index', 'domain' => $line['fqdn']], ['target' => '_blank', 'class' => 'btn btn-default btn-flat']) ?>
            <?php endif; ?>
        </div>
    </div>
</div>
