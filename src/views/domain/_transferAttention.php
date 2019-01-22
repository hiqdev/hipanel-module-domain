<?php

use yii\helpers\Html;

?>

<div class="row">
    <div class="col-md-5">
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <?= Yii::t('hipanel', 'Attention') ?>!
                </h3>
            </div>
            <div class="box-body">
                <p><?= Yii::t('hipanel:domain', 'The transfer will end within (6) six days if the current registrar of recorder does not cancel the request.') ?></p>
                <p><?= Yii::t('hipanel:domain', 'If you have any further questions, please {create_a_ticket}.', ['create_a_ticket' => Html::a(Yii::t('hipanel:domain', 'create a ticket'), ['@ticket/create'])]) ?></p>
            </div>
        </div>
    </div>
</div>
