<?php

use hipanel\widgets\AjaxModal;
use yii\bootstrap\Modal;
use yii\helpers\Html;

$buttonOptions = [
    'label' => '<i class="fa fa-fw fa-pencil"></i> ' . Yii::t('hipanel:domain', 'Change contacts'),
    'class' => 'btn btn-success btn-flat',
];
?>

<?php if ($model->isContactChangeable()) : ?>
    <?= AjaxModal::widget([
        'id' => 'set-contacts-modal',
        'bulkPage' => false,
        'header' => Html::tag('h4', Yii::t('hipanel:domain', 'Change contacts'), ['class' => 'modal-title']),
        'scenario' => 'set-contacts',
        'actionUrl' => ['bulk-set-contacts-modal', 'id' => $model->id],
        'size' => Modal::SIZE_LARGE,
        'toggleButton' => ['label' => '<i class="fa fa-fw fa-pencil"></i> ' . Yii::t('hipanel:domain', 'Change contacts'), 'class' => 'btn btn-sm btn-success btn-flat'],
    ]); ?>
<?php else: ?>
    <?= Html::button($buttonOptions['label'], array_merge($buttonOptions, ['disabled' => 'disabled'])); ?>
    <?= Html::tag('span', Yii::t('hipanel:domain', 'Can not change the contact information for this zone.'), ['class' => 'text-danger']) ?>
<?php endif; ?>
