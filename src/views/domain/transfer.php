<?php

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
?>
<?php $form = ActiveForm::begin([
    'id' => 'domain-transfer-single',
    'options' => [
    ],
//    'fieldConfig' => [
//        'template' => "{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
//    ],
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => $model->scenario]),
]) ?>

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
                    <div class="col-md-4">
                        <div class="form-group">
                            <?= $form->field($model, 'domain'); ?>
                        </div>
                    </div>
                    <!-- /.col-md-6 -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <?= $form->field($model, 'password'); ?>
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
                <?= $form->field($model, 'domains')->textarea(); ?>
                <p class="help-block">
                    <?= Yii::t('app', 'For separation of the domain and code use a space, a comma or a semicolon.') ?><br>
                    <?= Yii::t('app', 'Example') ?>:<br>
                    yourdomain.com uGt6shlad, <?= Yii::t('app', 'or') ?> yourdomain.com, uGt6shlad, <?= Yii::t('app', 'or') ?> yourdomain.com; uGt6shlad<br>
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

<?php ActiveForm::end() ?>
