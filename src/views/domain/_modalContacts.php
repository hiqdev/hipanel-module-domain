<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

$buttonOptions = [
    'label' => '<i class="fa fa-pencil"></i> ' . Yii::t('hipanel:domain', 'Change contacts'),
    'class' => 'btn btn-success btn-flat',
];
?>

<?php if ($model->isContactChangeable()) : ?>
    <?php Modal::begin([
        'id' => 'domain-contacts-modal',
        'header' => '<h4 class="modal-title">' . Yii::t('hipanel:domain', 'Change contacts') . '</h4>',
        'toggleButton' => $buttonOptions,
        'clientEvents' => [
            'show.bs.modal' => new JsExpression("
            function() {
                var ids = '{$model->id}';
                if (!ids) {
                    ids = jQuery('div[role=\"grid\"]').yiiGridView('getSelectedRows');
                }
                jQuery('#domain-contacts-modal .modal-body').load(
                    '" . Url::toRoute('modal-contacts-body') . "',
                    {ids: ids},
                    function() {
                        jQuery('#modal-save-contacts-button').show();
                    }
                );
        }"),
        ],
        'footer' => Html::button(Yii::t('hipanel', 'Save'), ['id' => 'modal-save-contacts-button', 'class' => 'btn btn-default', 'style' => 'display: none;']),
    ]); ?>
    <div class="progress">
        <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0"
             aria-valuemax="100" style="width: 100%">
            <span class="sr-only"><?= Yii::t('hipanel', 'loading...') ?></span>
        </div>
    </div>
    <?php Modal::end(); ?>
<?php else: ?>
    <?= Html::button($buttonOptions['label'], array_merge($buttonOptions, ['disabled' => 'disabled'])); ?>
    <?= Html::tag('span', Yii::t('hipanel:domain', 'Can not change the contact information for this zone.'), ['class' => 'text-danger']) ?>
<?php endif; ?>
