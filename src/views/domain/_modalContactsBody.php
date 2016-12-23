<?php

use hipanel\modules\domain\models\Domain;
use yii\helpers\Html;
use yii\helpers\Url;

$errorMsg = Yii::t('hipanel', 'An error occurred while sending request. Try to repeat this action later.');
$success = Yii::t('hipanel', 'Success');
$contactTablesLink = Url::toRoute(['get-contacts-by-ajax', 'id' => reset($domainContacts)['id']]);
$this->registerJs(<<<JS
var ajaxRequestStatus = 0;
jQuery('#modal-save-contacts-button').on('click', function(event) {
    event.preventDefault();
    if (ajaxRequestStatus == 0) {
        ajaxRequestStatus = 1;
        var btn = jQuery(this),
            form = jQuery('#set-contacts-form');
        btn.attr('disabled','disabled').addClass('disabled');

        jQuery.ajax({
            url: form.attr('action'),
            type: 'POST',
            dataType: 'json',
            timeout: 0,
            data: form.serialize(),
            error: function() {
                hipanel.notify.error('$errorMsg');
                ajaxRequestStatus = 0;
            },
            beforeSend: function() {
                jQuery('#set-contacts-form *').removeClass('has-error');
                jQuery('#set-contacts-form .help-block').text('');
                btn.attr('disabled','disabled').addClass('disabled');
            },
            complete: function() {
                btn.removeAttr('disabled').removeClass('disabled');
            },
            success: function(data) {
                btn.removeAttr('disabled').removeClass('disabled');
                if (data.errors) {
                    hipanel.notify.error(data.errors.title);
                    jQuery.each(data.errors, function(k, v) {
                        var elem = jQuery('#modal_' + k);
                        elem.closest('.form-group').addClass('has-error');
                        elem.next('.help-block').text(v);
                    })
                } else {
                    jQuery('#domain-contacts-modal').modal('hide');
                    hipanel.notify.success('$success');
                    jQuery('#contacts-tables').load('$contactTablesLink');
                }
                ajaxRequestStatus = 0;
            }
        });
    }
});
JS
);
?>

<?= Html::beginForm(Url::toRoute('set-contacts'), 'POST', ['id' => 'set-contacts-form', 'class' => 'form-horizontal']) ?>

<?php foreach ($domainContacts as $k => $v) : ?>
    <?php if (!in_array(Domain::getZone($v['domain']), ['ru', 'su', 'рф'])) : ?>
        <?= Html::hiddenInput('id[]', $k) ?>
    <?php endif ?>
<?php endforeach ?>

<?php foreach (Domain::contactTypesWithLabels() as $item => $label) : ?>
<div class="form-group">
    <?= Html::label($label, $item, ['class' => 'col-sm-4 control-label']) ?>
    <div class="col-sm-8">
        <?= Html::dropDownList($item, count($domainContacts) < 2 ? reset($domainContacts)[$item] : null, $modelContactInfo, ['prompt' => '--', 'id' => 'modal_' . $item, 'class' => 'form-control']) ?>
        <p class="help-block help-block-error"></p>
    </div>
</div>
<?php endforeach ?>

<?= Html::endForm() ?>
