<?php

/** @var string $domain */
/** @var array $availableZones */
/** @var \hipanel\modules\domain\models\Domain $model */

use hipanel\modules\domainchecker\assets\WhoisAsset;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = Yii::t('hipanel/domainchecker', 'Whois lookup');
$this->params['breadcrumbs'][] = $this->title;

if ($model->domain !== null) {
    WhoisAsset::register($this);
    $this->registerJs("$('#whois').whois({domain: '{$model->domain}'});");
}
?>
<div class="row">
    <div class="col-md-3 col-sm-12 col-xs-12">
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
                    'placeholder' => Yii::t('hipanel/domainchecker', 'Domain name'),
                    'class' => 'form-control',
                    'name' => 'domain',
                ]); ?>

                <?= Html::submitButton('<i class="fa fa-search"></i>&nbsp;&nbsp;' . Yii::t('hipanel/domainchecker', 'Search'), ['class' => 'btn btn-info btn-flat btn-block']); ?>
                <?php ActiveForm::end() ?>
                <div class="bg-warning md-mt-10" style="padding: 5px 7px">
                    <span class="text-bold"><?= Yii::t('hipanel/domainchecker', 'Available zones') ?>:</span><br>
                    com, net, name, cc, tv, org, info, pro, mobi, biz, xxx, porn, adult, sex, me, kiev.ua, com.ua, su, ru
                </div>
                <p class="md-mt-20">
                    <?= Yii::t('hipanel/domainchecker', 'WHOIS isn’t an acronym, though it may look like one. In fact, it is the system that provides information, who is responsible for a domain name.') ?>
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-9 col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <?= Yii::t('hipanel/domainchecker', 'Whois lookup result') ?>
                </h3>
                <div class="box-tools pull-right">
                </div>
            </div>
            <div class="box-body">
                <?php if (!$model->domain) : ?>
                    <div class="mailbox-read-message text-center">
                        <?= Yii::t('hipanel/domainchecker', 'You could check whois information here. Just enter domain in the form input.') ?>
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
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>


