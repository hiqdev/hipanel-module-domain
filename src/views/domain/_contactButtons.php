<?php

use hipanel\widgets\AjaxModal;
use yii\bootstrap\Modal;
use yii\helpers\Html;

?>

<?php if ($model->isContactChangeable()) : ?>
    <?= AjaxModal::widget([
        'id' => 'set-contacts-modal',
        'bulkPage' => false,
        'header' => Html::tag('h4', Yii::t('hipanel:domain', 'Change contacts'), ['class' => 'modal-title']),
        'scenario' => 'set-contacts',
        'actionUrl' => ['bulk-set-contacts-modal', 'id' => $model->id],
        'size' => Modal::SIZE_LARGE,
        'toggleButton' => ['label' => '<i class="fa fa-fw fa-pencil"></i> ' . Yii::t('hipanel:domain', 'Change contacts'), 'class' => 'btn btn-xs btn-success'],
    ]); ?>
<?php else: ?>
    <?= Html::tag('span', Yii::t('hipanel:domain', 'Can not change the contact information for this zone.'), ['class' => 'badge bg-yellow']) ?>
<?php endif; ?>
