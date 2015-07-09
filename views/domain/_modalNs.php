<?php
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

$ajaxNsLink = Url::toRoute('set-ns');

$this->registerJs(<<<JS
    var ids;
    var ajaxRequestNS = 0;
    jQuery('#modal-save-ns-button').on('click', function(event) {
        console.log(ids);
        var btn = jQuery(this);
        if (ids && ajaxRequestNS == 0) {
            ajaxRequestNS = 1;
            btn.attr('disabled','disabled').addClass('disabled');

            jQuery.ajax({
                url: '$ajaxNsLink',
                type: 'POST',
                dataType: 'json',
                timeout: 0,
                data: {ids: ids, nameservers: jQuery('#nameservers').val()},
                error: function() {
                    new PNotify({
                        text: "123",
                        type: 'error',
                        buttons: {
                            sticker: false
                        },
                        icon: false
                    });
                    ajaxRequestStatus = 0;
                },
                beforeSend: function() {
                    btn.attr('disabled','disabled').addClass('disabled');
                },
                complete: function() {
                    btn.removeAttr('disabled').removeClass('disabled');
                },
                success: function(data) {
                    btn.removeAttr('disabled').removeClass('disabled');

                    ajaxRequestStatus = 0;
                }
            });
        }
    });
JS
);

Modal::begin([
    'id' => 'ns-modal',
    'size' => Modal::SIZE_LARGE,
    'header' => '<h4 class="modal-title">' . Yii::t('app', 'Change NS') . '</h4>',
    'toggleButton' => [
        'label' => Yii::t('app', 'Set NS'),
        'class' => 'btn btn-default',
    ],
    'clientEvents' => [
        'show.bs.modal' => new JsExpression("
            function() {
                ids = jQuery('div[role=\"grid\"]').yiiGridView('getSelectedRows');
                jQuery('#ns-modal .modal-body').load(
                    '" . Url::toRoute('modal-ns-body') . "',
                    {ids: ids},
                    function() {
                        jQuery('#modal-save-contacts-button').show();
                    }
                );
            }
        ")
    ],
    'footer' => Html::button(Yii::t('app', 'Save'), ['id' => 'modal-save-ns-button', 'class' => 'btn btn-default']),
]); ?>

<div class="progress">
    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
        <span class="sr-only"><?= Yii::t('app', 'loading') ?>...</span>
    </div>
</div>

<?php Modal::end(); ?>
