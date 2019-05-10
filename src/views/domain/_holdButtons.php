<?php

use yii\helpers\Html;

$options = [
    'method' => 'post',
    'data-pjax' => '0',
];
?>
<?php if (!($model->isHolded()) && (Yii::$app->user->can('support') && Yii::$app->user->not($model->client_id) && Yii::$app->user->not($model->seller_id))) : ?>
    <li>
        <?= Html::a('<i class="fa fa-fw fa-bomb"></i> ' . Yii::t('hipanel:domain', 'Enable Hold'), ['@domain/enable-hold', 'id' => $model->id], $options) ?>
    </li>
<?php elseif (($model->isHolded() && $model->isActive() && Yii::$app->user->can('support') && $model->notDomainOwner())) : ?>
    <li>
        <?= Html::a('<i class="fa fa-fw fa-link"></i> ' . Yii::t('hipanel:domain', 'Disable Hold'), ['@domain/disable-hold', 'id' => $this->model->id], $options) ?>
    </li>
<?php endif; ?>
