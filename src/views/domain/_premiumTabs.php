<?php

/** @var \hipanel\modules\domain\models\Domain $model */

use hipanel\modules\domain\grid\DomainGridView;

?>

<div id="premium" class="tab-pane fade">
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
                <?= Yii::t('hipanel:domain', 'URL forwarding') ?>
            </h3>
        </div>
        <div class="panel-body">
            <?= $this->render('premium/urlfw', ['model' => $model]) ?>
        </div>
    </div>
</div>

<div id="email-forwarding" class="tab-pane fade">
    <div class="panel panel-default">
        <div class="panel-heading premium-panels-heading">
            <h3 class="panel-title">
                <?= Yii::t('hipanel:domain', 'Email forwarding') ?>
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
                <?= Yii::t('hipanel:domain', 'Parking') ?>
            </h3>
        </div>
        <div class="panel-body">
            <?= $this->render('premium/park', ['model' => $model]) ?>
        </div>
    </div>
</div>


