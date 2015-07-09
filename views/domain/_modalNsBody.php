<?php
use yii\helpers\Html;
use yii\helpers\Url;

//\yii\helpers\VarDumper::dump($domainsList, 10, true);
$this->registerJs(<<<JS
jQuery('.radio-check').iCheck({
    checkboxClass: 'icheckbox_minimal-blue',
    radioClass: 'iradio_minimal-blue'
});

jQuery('.radio-check').on('click', function() {
    var value = jQuery.trim(this.value);
    jQuery.not(this).prop('disabled', value.length != 0);
});

jQuery('input.radio').on('switchChange.bootstrapSwitch', function(event, state) {
    //console.log(this); // DOM element
    //console.log(event); // jQuery event
    //console.log(state); // true | false

    var elem = jQuery(this),
        family = elem.data('family'),
        input = jQuery('.input').not('.input[data-family="' + family + '"]');

    if (state == true) {
        input.attr('readonly', 1);
    } else {
        input.removeAttr('readonly');
    }

    console.log(family);


});
JS
);

?>

<?= Html::beginForm(Url::toRoute('set-contacts'), 'POST', ['id' => 'set-ns-form', 'class' => 'form-horizontal']) ?>
<div class="row">
    <div class="col-sm-offset-9 sol-sm-3 hidden-xs text-right margin-bottom"><?= Html::tag('span', Yii::t('app', 'Use the same ns for all domains'), ['class' => 'label label-info']) ?></div>
</div>
<?php foreach ($domainsList as $id => $domainInfo) : ?>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-4 control-label"><?= $domainInfo['domain'] ?></label>
        <div class="col-sm-6">
            <?= Html::input('text', 'Domain[' . $id . '][nameservers]', $domainInfo['nss'], ['class' => 'form-control input', 'data-family' => $id, 'placeholder' => 'Type NS servers here...']) ?>
        </div>
        <div class="col-sm-2">
            <?= \hiqdev\bootstrap_switch\BootstrapSwitch::widget([
                'type' => \hiqdev\bootstrap_switch\BootstrapSwitchAsset::TYPE_RADIO,
                'name' => 'check',
                'items' => [$id => null],
                'options' => [
                    'data-family' => $id,
                    'class' => 'radio',
                ],
                'pluginOptions' => [
                    'size' => 'normal',
                    'radioAllOff' => true,
                    'onText' => Yii::t('app', 'For all')
                ]

            ]) ?>
        </div>
    </div>
<?php endforeach; ?>
<?= Html::endForm() ?>
