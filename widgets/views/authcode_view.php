<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

$loadingText = Yii::t('app', 'loading') . '...';
$getPasswordUrl = Url::toRoute('get-password');
$changePasswordUrl = Url::toRoute('change-password');

$view->registerJs(<<<JS
jQuery('#pincode-modal').on('shown.bs.modal', function () {
  $('#pincode').focus();
});

jQuery('#get-authcode-button').on('click', function() {
    jQuery('#pincode-modal .form-group').removeClass('has-error');
    jQuery('#pincode-modal .help-block').text('');

    var btn = jQuery(this).button('$loadingText');
    var pin = jQuery('#pincode').val();

    jQuery.ajax({
        url: '$getPasswordUrl',
        type: 'POST',
        dataType: 'json',
        timeout: 0,
        data: {id: '$domainId', pincode: pin},
        success: function(data) {
            btn.button('reset');
            if (data.status == 'error') {
                jQuery('#pincode-modal .form-group').addClass('has-error');
                jQuery('#pincode-modal .help-block').text(data.info);
            } else {
                jQuery('#pincode-modal').modal('hide');
                jQuery('#modal-show-button').attr('disabled','disabled');
                jQuery('#pincode-static').text(data.password);
            }
        }
    });
});

jQuery('#change-password-button').on('click', function() {
    jQuery('#pincode-modal .help-block').text('');
    var btn = jQuery(this).button('$loadingText');

    jQuery.ajax({
        url: '$changePasswordUrl',
        type: 'POST',
        dataType: 'json',
        timeout: 0,
        data: {id: '$domainId'},
        success: function(data) {
            btn.button('reset');
            if (data.status == 'error') {
                jQuery('#pincode-modal .help-block').text(data.info);
            } else {
                jQuery('#modal-show-button').removeAttr('disabled');
                jQuery('#pincode-static').text('*******');
            }
        }
    });
});
JS
, View::POS_READY); ?>
<?= Html::beginForm([
    'options' => [
        'class' => 'form-horizontal'
    ]
]); ?>
<?= Html::hiddenInput('id', $domainId); ?>
<div class="row">
    <!-- /.col-lg-6 -->
    <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
        <div class="input-group">
            <p id="pincode-static" class="form-control-static bg-warning text-center" style="vertical-align: middle;font-size: larger;">*******</p>
            <span class="input-group-btn">
                <?= Html::button(Yii::t('app', 'Show'), ['id' => 'modal-show-button', 'data-toggle' => 'modal', 'data-target' => '#pincode-modal', 'class' => 'btn btn-default']); ?>
                <?= Html::button(Yii::t('app', 'Copy'), ['id' => 'copy-to-clipboard', 'class' => 'btn btn-default', 'style' => 'display: none;']); ?>
                <?= Html::button(Yii::t('app', 'Change password'), ['id' => 'change-password-button', 'class' => 'btn btn-default']); ?>
            </span>
        </div>
        <!-- /input-group -->
    </div>
    <!-- /.col-lg-6 -->
</div><!-- /.row -->
<?= Html::endForm(); ?>

<div id="pincode-modal" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Enter pincode'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <?= Html::passwordInput('pincode', null, ['class' => 'form-control', 'id' => 'pincode', 'placeholder' => '****']) ?>
                    <p class="help-block"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('app', 'Close'); ?></button>
                <button type="button" class="btn btn-primary" id="get-authcode-button"><?= Yii::t('app', 'Send'); ?></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
