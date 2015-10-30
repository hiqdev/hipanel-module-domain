<?php
use hipanel\grid\GridView;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Domain check');
$this->breadcrumbs->setItems([
    $this->title,
]);
?>

<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><?= Yii::t('app', 'Domain check'); ?></h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <?= Html::beginForm('', 'post', ['class' => 'inline-form']) ?>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <?= Html::textInput('domain_name', $_POST['domain_name'], ['class' => 'form-control input-lg', 'placeholder' => Yii::t('app', 'Domain search...')]) ?>
                </div>
            </div>
            <!-- /.col-md-8 -->
            <div class="col-md-3">
                <div class="form-group">
                    <?= Html::dropDownList('zone', $_POST['zone'], $dropDownZonesOptions, ['class' => 'form-control input-lg']) ?>
                </div>
            </div>
            <!-- /.col-md-3 -->
            <div class="col-md-1"><?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-default btn-lg btn-block']); ?></div>
            <!-- /.col-md-1 -->
        </div>
        <!-- /.row -->
        <?= Html::endForm(); ?>
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