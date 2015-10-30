<?php
use hipanel\grid\GridView;
use hipanel\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use hipanel\widgets\Pjax;

$this->title = Yii::t('app', 'Domain check');
$this->breadcrumbs->setItems([
    $this->title,
]);
?>
<?php Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true])) ?>
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><?= Yii::t('app', 'Domain check'); ?></h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <?php $form = ActiveForm::begin([
            'id' => 'check-domain',
            'fieldConfig' => [
                'template' => "{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            ],
            'enableAjaxValidation' => true,
            'validationUrl' => Url::toRoute(['validate-form', 'scenario' => $model->scenario]),
        ]) ?>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <?= $form->field($model, 'domain')->textInput(['placeholder' => Yii::t('app', 'Domain search...'), 'class' => 'form-control input-lg']); ?>
                </div>
            </div>
            <!-- /.col-md-8 -->
            <div class="col-md-3">
                <div class="form-group">
                    <?= $form->field($model, 'zone')->dropDownList($dropDownZonesOptions, ['class' => 'form-control input-lg']); ?>
                </div>
            </div>
            <!-- /.col-md-3 -->
            <div class="col-md-1"><?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-default btn-lg btn-block']); ?></div>
            <!-- /.col-md-1 -->
        </div>
        <!-- /.row -->
        <?php ActiveForm::end() ?>
    </div>
    <!-- /.box-body -->
</div><!-- /.box -->

<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><?= Yii::t('app', 'Check results'); ?></h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <?= GridView::widget([
            'dataProvider' => $domainCheckDataProvider,
            'layout' => "{items}\n{pager}",
            'columns' => [
                'zone',
                'registration',
                'renewal',
                'transfer',
                'actions' => [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{but}',
                    'header' => Yii::t('app', 'Action'),
                    'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                    'buttons' => [
                        'buy' => function ($url, $model, $key) {
                            return Html::a(Yii::t('app', 'Add to cart'), ['remove', 'id' => $model['id']]);
                        },
                    ],
                ],
            ],
        ]); ?>
    </div>
    <!-- /.box-body -->
</div><!-- /.box -->
<?php Pjax::end() ?>