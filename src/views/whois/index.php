<?php

/** @var string $domain */
/** @var array $availableZones */
/** @var \hipanel\modules\domain\models\Whois $model */
use hipanel\modules\domain\assets\WhoisAsset;
use hipanel\widgets\ArraySpoiler;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = Yii::t('hipanel:domain', 'WHOIS lookup');
$this->params['breadcrumbs'][] = $this->title;

if ($model->domain !== null) {
    WhoisAsset::register($this);
    $this->registerJs("$('#whois').whois({domain: '{$model->domain}'});");
}

$this->registerJs(<<<'JS'

    var goToTheCartHandler = function(evt) {
        evt.preventDefault();
        window.location.replace($(this).prop('href'));
        return false;
    }

    var addToCartHandler = function (evt) {
        evt.preventDefault();
        var addToCartElem = $(this);
        addToCartElem.button('loading')
        $.post(addToCartElem.data('domain-url'), function() {
            hipanel.updateCart(function() {
                addToCartElem.button('complete');
                setTimeout(function () {
                    addToCartElem.one('click', goToTheCartHandler);
                }, 0);
            });
        });

        return false;
    }

    $(document).on('click', 'a.add-to-cart-button', addToCartHandler);
JS
);

?>
<div class="row">
    <div class="col-md-3 col-sm-12">
        <div class="box box-solid">
            <div class="box-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'whois-lookup',
                    'action' => Url::toRoute('whois/index'),
                    'method' => 'get',
                    'options' => [
                        'data-pjax' => false,
                    ],
                    'fieldConfig' => [
                        'template' => "{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                    ],
                ]) ?>

                <?= $form->field($model, 'domain')->textInput([
                    'placeholder' => Yii::t('hipanel:domain', 'Domain name'),
                    'class' => 'form-control',
                    'name' => 'domain',
                    'value' => $model->getDomainAsUtf8(),
                ]) ?>

                <?= Html::submitButton('<i class="fa fa-search"></i>&nbsp;&nbsp;' . Yii::t('hipanel:domain', 'Search'), ['class' => 'btn btn-info btn-flat btn-block xs-mb-10']) ?>
                <?php ActiveForm::end() ?>

                <?php if (!empty($availableZones)) : ?>
                    <div class="bg-warning md-mt-10 xs-mb-10" style="padding: 5px 7px">
                        <span class="text-bold"><?= Yii::t('hipanel:domain', 'Available zones') ?>:</span><br>
                        <?= ArraySpoiler::widget([
                            'data' => $availableZones,
                            'visibleCount' => count($availableZones),
                        ]) ?>
                    </div>
                <?php endif ?>
                <p class="md-mt-20 text-justify">
                    <?= Yii::t('hipanel:domain', 'WHOIS isn’t an acronym, though it may look like one. In fact, it is the system that provides information, who is responsible for a domain name.') ?>
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-9 col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <?= Yii::t('hipanel:domain', 'WHOIS lookup result') ?>
                </h3>
                <div class="box-tools pull-right">
                </div>
            </div>
            <div class="box-body">
                <?php if (!$model->domain) : ?>
                    <div class="mailbox-read-message text-center">
                        <?= Yii::t('hipanel:domain', 'You can check WHOIS information here. Just enter domain name in the form input.') ?>
                    </div>
                    <?php else: ?>
                    <div id="whois">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100"
                                 aria-valuemin="0"
                                 aria-valuemax="100" style="width: 100%">
                            </div>
                        </div>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>


