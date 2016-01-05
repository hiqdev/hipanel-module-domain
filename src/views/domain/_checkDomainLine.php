<?php
/**
 * @var $line array
 * @var $state
 */
use yii\helpers\Html;
use hipanel\modules\domain\models\Domain;

?>

<div class="domain-line" data-domain="<?= $line['full_domain_name'] ?>" data-filter="<?= Domain::setIsotopeFilterValue($line['zone']) ?> <?= ($state === 'available') ? '.available' : '.unavailable' ?>">
    <div class="col-md-6">
        <?php if ($state) : ?>
            <span class="domain-img"><i class="fa fa-globe fa-lg"></i></span>
        <?php else : ?>
            <span class="domain-img"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i></span>
        <?php endif; ?>

        <?php if ($state === 'available') : ?>
            <span class="domain-name"><?= $line['domain'] ?></span><span class="domain-zone">.<?= $line['zone'] ?></span>
        <?php elseif ($state === 'unavailable') : ?>
            <span class="domain-name muted"><?= $line['domain'] ?></span><span class="domain-zone muted">.<?= $line['zone'] ?></span>
        <?php else : ?>
            <span class="domain-name muted"><?= $line['domain'] ?></span><span class="domain-zone muted">.<?= $line['zone'] ?></span>
        <?php endif; ?>
    </div>
    <div class="col-md-4 text-center">
        <span class="domain-price">
            <?php if ($state === 'available') : ?>
                <!--del>0.00 €</del-->
                <?= Yii::$app->formatter->format($line['tariff']->price, ['currency', $line['tariff']->currency]) ?>
                <span class="domain-price-year">/<?= Yii::t('app', 'year') ?></span>

            <?php elseif ($state === 'unavailable') : ?>
                <span class="domain-taken">
                    <?= Yii::t('app', 'Domain is not available') ?>
                </span>
            <?php else : ?>

            <?php endif; ?>
        </span>
    </div>
    <div class="col-md-2">
        <?php if ($state === 'available') : ?>
            <?= Html::a('<i class="fa fa-cart-plus fa-lg"></i>&nbsp; ' . Yii::t('app', 'Add to cart'), ['add-to-cart-registration', 'name' => $line['full_domain_name']], ['data-pjax' => 0, 'class' => 'btn btn-flat bg-olive']) ?>
        <?php elseif ($state === 'unavailable') : ?>
            <?= Html::a(Yii::t('app', 'WHOIS'), 'https://ahnames.com/ru/search/whois/#' . $line['full_domain_name'], ['target' => '_blank', 'class' => 'btn btn-default btn-flat']) ?>
        <?php else : ?>

        <?php endif; ?>
    </div>
</div>