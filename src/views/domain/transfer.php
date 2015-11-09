<?php

use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Domain transfer');
$this->breadcrumbs->setItems([
    $this->title,
]);

$this->registerCss('
.step {
    font: 28px/24px "RobotoBold",Arial,sans-serif;
    color: #c7c7c7;
    margin-bottom: 1em;
    text-align: center;
}
');
$this->registerJs(<<<JS
    jQuery('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        jQuery('#' + e.relatedTarget.getAttribute('href').substr(1)).find('input:text, textarea').val(''); //e.target
    });
JS
);
$id = $model->id ? : 0 ;
?>
<?php $form = ActiveForm::begin([
    'id' => 'domain-transfer-single',
    'action' => ['transfer'],
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => $model->scenario]),
]) ?>
<?php if (!Yii::$app->session->getFlash('transferGrid')) : ?>
<div class="box box-solid">
    <!-- /.box-header -->
    <div class="box-body">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#single" data-toggle="tab"><?= Yii::t('app', 'Single domain transfer') ?></a></li>
            <li><a href="#bulk" data-toggle="tab"><?= Yii::t('app', 'Bulk domain transfer') ?></a></li>
        </ul>

        <div class="tab-content" style="padding-top: 1em">

            <div class="tab-pane active" id="single">
                <div class="row">
                    <div class="col-md-1 step">1.</div>
                    <!-- /.col-md-1 -->
                    <div class="col-md-11"><?= Yii::t('app', 'Remove whois protection from the current registrar.') ?></div>
                    <!-- /.col-md-11 -->
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-md-1 step">2.</div>
                    <!-- /.col-md-1 -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <?= $form->field($model, "[$id]domain"); ?>
                        </div>
                    </div>
                    <!-- /.col-md-6 -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <?= $form->field($model, "[$id]password"); ?>
                        </div>
                    </div>
                    <!-- /.col-md-5 -->
                </div>
                <div class="row">
                    <div class="col-md-1 step">3.</div>
                    <!-- /.col-md-1 -->
                    <div class="col-md-11">
                        <?= Yii::t('app', 'An email was sent to your email address specified in Whois. To start the transfer, click on the link in the email.') ?>
                    </div>
                    <!-- /.col-md-11 -->
                </div>
                <!-- /.row -->
            </div>

            <div class="tab-pane" id="bulk">
                <?= $form->field($model, "[$id]domains")->textarea(); ?>
                <p class="help-block">
                    <?= Yii::t('app', 'For separation of the domain and code use a space, a comma or a semicolon.') ?><br>
                    <?= Yii::t('app', 'Example') ?>:<br>
                    <b>yourdomain.com uGt6shlad, <?= Yii::t('app', 'or') ?> yourdomain.com, uGt6shlad, <?= Yii::t('app', 'or') ?> yourdomain.com; uGt6shlad</b></b><br>
                    <?= Yii::t('app', 'each pair (domain + code) should be written with a new line') ?>
                </p>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <?= Html::submitButton(Yii::t('app', 'Transfer'), ['class' => 'btn btn-default']); ?>

    </div>
    <!-- /.box-body -->
</div><!-- /.box -->
<?php else : ?>
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Yii::t('app', 'Cannot transfer following domains'); ?></h3>
        </div>
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $transferDataProvider['success'],
                'layout' => "{items}\n{pager}",
                'rowOptions' => function ($model, $key, $index, $grid) {
                    return ['class' => 'check-item', 'data-domain' => $model->domain];
                },
                'columns' => [
                    'domain',
                    'password',
                ],
            ]); ?>
        </div>
    </div>
<?php endif; ?>
<?php ActiveForm::end() ?>
