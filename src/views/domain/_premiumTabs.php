<?php

/** @var \hipanel\modules\domain\models\Domain $model */
/** @var array $forwardingOptions */

use hipanel\modules\domain\assets\PremiumFeatureAsset;
use hipanel\modules\domain\grid\DomainGridView;

PremiumFeatureAsset::register($this);

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
    <?= $this->render('premium/urlfw', compact('model', 'forwardingOptions')) ?>
</div>

<div id="email-forwarding" class="tab-pane fade">
    <?= $this->render('premium/mailfw', ['model' => $model]) ?>
</div>

<div id="parking" class="tab-pane fade">
    <?= $this->render('premium/parking', ['model' => $model]) ?>
</div>


