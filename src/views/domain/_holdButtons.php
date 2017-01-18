<?php
use hipanel\modules\domain\models\Domain;
use yii\helpers\Html;

$options = [
    'method' => 'post',
    'data-pjax' => '0',
];
?>
<?php if (!($model->is_holded) && (Yii::$app->user->can('support') && Yii::$app->user->not($model->client_id) && Yii::$app->user->not($model->seller_id))) : ?>
    <li>
        <?= Html::a('<i class="fa fa-fw fa-bomb"></i> ' . Yii::t('hipanel:domain', 'Enable Hold'), ['@domain/enable-hold', 'id' => $model->id], $options) ?>
    </li>
<?php elseif (($model->is_holded && in_array($model->state, ['ok', 'expired'], true) && Yii::$app->user->can('support') && Domain::notDomainOwner($model))) : ?>
    <li>
        <?= Html::a('<i class="fa fa-fw fa-link"></i> ' . Yii::t('hipanel:domain', 'Disable Hold'), ['@domain/disable-hold', 'id' => $this->model->id], $options) ?>
    </li>
<?php endif; ?>
