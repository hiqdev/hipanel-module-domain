<?php

/** @var \hipanel\modules\domain\models\Domain $model */
/** @var array $forwardingOptions */

use hipanel\modules\domain\grid\DomainGridView;

\hipanel\modules\domain\assets\PremiumFeatureAsset::register($this);

$this->registerJs("
$(document).on('click', '.pf-update-form-close', function () {
    $(this).parents('tr').eq(0).remove();
});
");

?>

<div id="premium" class="tab-pane active">
    <?= DomainGridView::detailView([
        'boxed' => false,
        'model' => $model,
        'columns' => [
            'is_premium',
            'premium_autorenewal',
        ],
    ]) ?>
</div>

<div id="url-forwarding" class="tab-pane fade">
    <div class="panel panel-default">
        <div class="panel-heading premium-panels-heading">
            <h3 class="panel-title">
                <?= Yii::t('hipanel.domain.premium', 'URL forwarding') ?>
            </h3>
        </div>
        <div class="panel-body">
            <?= $this->render('premium/urlfw', compact('model', 'forwardingOptions')) ?>
        </div>
    </div>
</div>

<div id="email-forwarding" class="tab-pane fade">
    <div class="panel panel-default">
        <div class="panel-heading premium-panels-heading">
            <h3 class="panel-title">
                <?= Yii::t('hipanel.domain.premium', 'Email forwarding') ?>
            </h3>
        </div>
        <div class="panel-body">
            <?= $this->render('premium/mailfw', ['model' => $model]) ?>
        </div>
    </div>
</div>

<div id="parking" class="tab-pane fade">
    <div class="panel panel-default">
        <div class="panel-heading premium-panels-heading">
            <h3 class="panel-title">
                <?= Yii::t('hipanel.domain.premium', 'Parking') ?>
            </h3>
        </div>
        <div class="panel-body">
            <?= $this->render('premium/parking', ['model' => $model]) ?>
        </div>
    </div>
</div>


