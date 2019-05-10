<?php

/** @var string $domains */
use yii\helpers\Html;

$this->title = count(explode(',', $domains)) > 1 ? Yii::t('hipanel:domain', 'Domains transfer canceled') : Yii::t('hipanel:domain', 'Domain transfer was canceled');
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
                    <p><?= Yii::t('hipanel:domain', 'The money for the canceled transaction will be returned to your account.') ?></p>
                    <p><?= Yii::t('hipanel:domain', 'If you have any further questions, please {create_a_ticket}.', ['create_a_ticket' => Html::a(Yii::t('hipanel:domain', 'create a ticket'), ['@ticket/create'])]) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
