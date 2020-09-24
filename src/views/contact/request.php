<?php

/**
 * @var \yii\web\View $this
 * @var bool $requestPassport
 */
use hipanel\widgets\AjaxModal;
use yii\bootstrap\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;

$this->title = Yii::t('cart', 'Order execution');

$contactInfoUrl = Url::to(['@contact/short-view']);

?>

<?php \hipanel\widgets\Box::begin([
    'title' => $requestRegistrant
        ? Yii::t('hipanel:domain', 'In order to finish domain registration, please, select contact data')
        : Yii::t('hipanel:domain', 'In order to finish domain registration, we ask you to fill missing contact data'),
    'options' => [
        'class' => 'box-warning',
    ],
]) ?>

<?php if ($requestPassport) : ?>
    <h5>
    <?= Yii::t('hipanel:domain', 'According to the rules of domain registration in .RU zone ({link}), you have to fill in your passport data.', [
        'link' => Html::a(
            Yii::t('hipanel:domain', 'No. 2011-18/81 from 2011-10-05 p.9.2.5.'),
            'http://cctld.ru/files/pdf/docs/rules_ru-rf.pdf?v=2',
            ['target' => '_blank', 'rel' => 'nofollow']
        ),
    ]) ?>
    </h5>
<?php endif ?>

<h4><?= Yii::t('hipanel:domain', 'You can either:') ?></h4>

<?= AjaxModal::widget([
    'id' => 'contact-edit',
    'header' => Html::tag('h4', Yii::t('hipanel:domain', 'Edit contact'), ['class' => 'modal-title']),
    'scenario' => 'push',
    'actionUrl' => ['@contact/update'],
    'size' => Modal::SIZE_LARGE,
    'toggleButton' => false,
    'clientEvents' => ['show.bs.modal' => false],
]) ?>
<?= AjaxModal::widget([
    'id' => 'contact-create',
    'header' => Html::tag('h4', Yii::t('hipanel:domain', 'Create contact'), ['class' => 'modal-title']),
    'scenario' => 'push',
    'actionUrl' => ['@contact/create'],
    'size' => Modal::SIZE_LARGE,
    'toggleButton' => false,
    'clientEvents' => ['show.bs.modal' => false],
]) ?>

<?php \yii\bootstrap\ActiveForm::begin([
    'id' => 'contact-management-form',
    'options' => [
        'class' => 'col-md-6',
    ],
]) ?>
    <div>
        <label>
            <?= Html::radio('action', false, [
                'value' => 'update',
                'ref' => '#contact-edit',
                'checked' => 'checked',
                'data-url' => Url::to(['@domain-contact/update', 'requestPassport' => (bool) $requestPassport]),
            ]) ?>
            <?= Yii::t('hipanel:domain', 'Select an existing contact, update it and use it') ?>

            <?= \hipanel\modules\client\widgets\combo\ContactCombo::widget([
                'inputOptions' => ['id' => 'contact-combo'],
                'model' => new \yii\base\DynamicModel(['id' => $registrant]),
                'attribute' => 'id',
                'hasId' => true,
                'formElementSelector' => 'div',
                'filter' => [
                    'client_id' => [
                        'format' => Yii::$app->user->id,
                    ],
                ],
            ]) ?>
        </label>
    </div>
    <div>
        <label>
            <?= Html::radio('action', false, [
                'value' => 'create',
                'ref' => '#contact-create',
                'data-url' => Url::to(['@domain-contact/create', 'requestPassport' => (bool) $requestPassport]),
            ]) ?>
            <?= Yii::t('hipanel:domain', 'Create new contact and use it') ?>
        </label>
    </div>
    <br/>
    <div>
        <?= Html::submitButton(Yii::t('hipanel', 'Ok'), ['class' => 'btn btn-info']) ?>
    </div>
<?php \yii\bootstrap\ActiveForm::end() ?>
<div id='contact-combo-info' class="col-md-6">
</div>
<?php \hipanel\widgets\Box::end() ?>

<?php

$this->registerJs(<<<JS
$(function () {
    var form = $('#contact-management-form');

    form.find('input[value=update]').on('change', function () {
        $('#contact-combo').select2('open');
    });

    form.on('change', function (event) {
        var input = form.find('input[name=action]:checked'),
            params = {};

        if (input.val() === 'create') {
            $('#contact-combo-info').html('');
            return false;
        }

        if (!$('#contact-combo').val()) {
            $('#contact-combo-info').html('');
            return false;
        }

        params.id = $('#contact-combo').val();

        $.get('$contactInfoUrl', params).done(function (data) {
            $('#contact-combo-info').html(data);
        });
    });

    form.on('beforeSubmit', function (event) {
        var input = form.find('input[name=action]:checked'),
            modalSelector = input.attr('ref'),
            params = {};

        if (input.val() === 'update') {
            params.id = $('#contact-combo').val();
        }

        $(modalSelector).modal('show');
        $.get(input.data('url'), params).done(function (data) {
            $(modalSelector + ' .modal-body').html(data);
        });

        return false;
    });

    form.trigger('change');
}());
JS
);
