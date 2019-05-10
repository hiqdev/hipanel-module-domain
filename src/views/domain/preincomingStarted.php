<?php

/** @var string $domains */
use yii\helpers\Html;

$this->title = count(explode(',', $domains)) > 1 ? Yii::t('hipanel:domain', 'Transfer of these domains started') : Yii::t('hipanel:domain', 'Domain transfer started');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel', 'Domains'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="shared-table">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="box box-widget">
                <div class="box-header with-border">
                    <?= Html::tag('h3', Html::encode($domains), [
                        'class' => 'box-title',
                        'style' => 'text-transform: uppercase; font-weight: bold;',
                    ]) ?>
                </div>
                <div class="box-body">
                    <p><?= Yii::t('hipanel:domain', 'The transfer will end within (6) six days if the current registrar of recorder does not cancel the request.') ?></p>
                    <p><?= Yii::t('hipanel:domain', 'If you have any further questions, please {create_a_ticket}.', ['create_a_ticket' => Html::a(Yii::t('hipanel:domain', 'create a ticket'), ['@ticket/create'])]) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
