<?php

use hipanel\modules\domain\models\Domain;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/**
 * @var View $this
 * @var Domain $model
 * @var boolean $askPincode
 */

?>

<?= Html::beginForm([
    'options' => [
        'class' => 'form-horizontal',
    ],
]); ?>
<?= Html::hiddenInput('id', $model->id); ?>
<div class="row">
    <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
        <div id="authcode-form" class="input-group">
            <p id="authcode-static" class="form-control-static bg-warning text-center" style="vertical-align: middle; font-size: larger;">
                *******
            </p>
            <span class="input-group-btn">
                <?= Html::button(Yii::t('hipanel', 'Show'), [
                    'id' => 'get-authcode-button',
                    'class' => 'btn btn-default',
                    'data-loading-text' => Yii::t('hipanel:domain', 'Getting code...'),
                    'data-id' => $model->id,
                    'data-url' => Url::to(['@domain/get-password']),
                    'data-error-message' => Yii::t('hipanel', 'An error occurred. Try again please.'),
                    'data-ask-pincode' => $askPincode
                ]); ?>
                <?php if ($model->canRenew()) : ?>
                    <?= Html::button(Yii::t('hipanel:domain', 'Generate new'), [
                        'id' => 'change-password-button',
                        'class' => 'btn btn-default',
                        'data-loading-text' => Yii::t('hipanel:domain', 'Code generation...'),
                        'data-id' => $model->id,
                        'data-url' => Url::to(['@domain/regen-password']),
                        'data-success-message' => Yii::t('hipanel:domain', 'The password has been changed')
                    ]) ?>
                <?php endif ?>
                ?>
            </span>
            <p class="help-block"></p>
        </div>
    </div>
</div>
<?= Html::endForm(); ?>

<?php

$this->registerJs(<<<JS
$('#get-authcode-button').on('click', function() {
    var btn = $(this),
        authCode = jQuery('#authcode-static');
    
    var act = function (pincode) {
        var data = {id: btn.data('id')};
        if (pincode) data.pincode = pincode;

        $.ajax({
            url: btn.data('url'),
            type: 'POST',
            dataType: 'json',
            timeout: 0,
            beforeSend: function () {
                btn.button('loading');
            },
            data: data,
            success: function (data) {
                if (data.status == 'error') {
                    hipanel.notify.error(data.info);
                    return;
                }

                btn.button('reset');
                setTimeout(function () {
                    // https://github.com/twbs/bootstrap/issues/6242
                    btn.attr('disabled', 'disabled');
                }, 0);

                authCode.text(data.password);
            },
            error: function () {
                btn.button('reset');
                hipanel.notify.error(btn.data('error-message'));
            }
        });
    };

    if (btn.data('ask-pincode')) {
        btn.pincodePrompt().then(act);
        return;
    }

    act();
});

$('#change-password-button').on('click', function() {
    var btn = $(this),
        authCode = $('#authcode-static'),
        authBtn = $('#get-authcode-button');
    
    $.ajax({
        url: btn.data('url'),
        type: 'POST',
        dataType: 'json',
        timeout: 0,
        data: {
            Domain: {id: btn.data('id')}
        },
        beforeSend: function () {
            btn.button('loading');
        },
        complete: function () {
            btn.button('reset');
        },
        success: function (data) {
            if (data.status == 'error') {
                hipanel.notify.error(data.info);
            }
            
            authBtn.removeAttr('disabled');
            authCode.text('*******');
            hipanel.notify.success(btn.data('success-message'))
        }
    });
});
JS
    , View::POS_READY); ?>
