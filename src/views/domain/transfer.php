<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Domain transfer');
$this->breadcrumbs->setItems([
    $this->title,
]);
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
                    <div class="col-md-6">
                        <div class="form-group">
                            <?= $form->field($model, 'domain'); ?>
                        </div>
                    </div>
                    <!-- /.col-md-6 -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <?= $form->field($model, 'password'); ?>
                        </div>
                    </div>
                    <!-- /.col-md-6 -->
                </div>
            </div>

            <div class="tab-pane" id="bulk">
                <?= $form->field($model, 'domains')->textarea(); ?>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <?= Html::submitButton(Yii::t('app', 'Transfer'), ['class' => 'btn btn-default']); ?>
    </div>
    <!-- /.box-body -->
</div><!-- /.box -->

<?php ActiveForm::end() ?>
