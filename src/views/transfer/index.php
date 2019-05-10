<?php

use hipanel\modules\domain\models\Domain;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/**
 * @var \yii\web\View $this
 * @var Domain $model
 * @var Domain[] $models
 * @var \yii\data\ArrayDataProvider $transferDataProvider
 */
$this->title = Yii::t('hipanel:domain', 'Domain transfer');
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('
.step {
    font: 28px/24px "RobotoBold",Arial,sans-serif;
    color: #c7c7c7;
    margin-bottom: 1em;
    text-align: center;
}
');
$this->registerJs(<<<'JS'
    $('#domain-transfer-single').on('submit', function (e) {
        $(this).find('.tab-pane').not('.active').find('input:text, textarea').val('');
    });
JS
);
$id = $model->id ?: 0;
?>

<?php if (!Yii::$app->session->getFlash('transferGrid')) : ?>
    <?php $form = ActiveForm::begin([
        'id' => 'domain-transfer-single',
        'action' => ['transfer'],
        'enableAjaxValidation' => true,
        'enableClientValidation' => false,
        'validationUrl' => Url::toRoute(['validate-form', 'scenario' => $model->scenario]),
    ]) ?>
    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#single"
                                          data-toggle="tab"><?= Yii::t('hipanel:domain', 'Domain transfer') ?></a></li>
                    <li><a href="#bulk" data-toggle="tab"><?= Yii::t('hipanel:domain', 'Bulk domain transfer') ?></a></li>

                </ul>

                <div class="tab-content" style="padding-top: 1em">

                    <div class="tab-pane active" id="single">
                        <div class="row">
                            <div class="col-md-1 step">1.</div>
                            <!-- /.col-md-1 -->
                            <div
                                class="col-md-11"><?= Yii::t('hipanel:domain', 'Remove WHOIS protection from the current registrar.') ?></div>
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
                                <?= Yii::t('hipanel:domain', 'An email was sent to your email address specified in Whois. To start the transfer, click on the link in the email.') ?>
                            </div>
                            <!-- /.col-md-11 -->
                        </div>
                        <!-- /.row -->
                    </div>

                    <div class="tab-pane" id="bulk">
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, "[$id]domains")->textarea(['rows' => 7]); ?>
                            </div>
                            <div class="col-md-6 lg-mt-20 md-mt-20 sm-mt-20">
                                <p class="help-block">
                                    <?= Yii::t('hipanel:domain', 'For separation of the domain and code use a space, a comma or a semicolon.') ?>
                                    <?= Yii::t('hipanel:domain', 'Example') ?>:<br>
                                    <b>yourdomain.com uGt6shlad</b><br>
                                    <?= Yii::t('hipanel:domain', 'or') ?><br>
                                    <b>yourdomain.com, uGt6shlad</b><br>
                                    <?= Yii::t('hipanel:domain', 'or') ?><br>
                                    <b>yourdomain.com; uGt6shlad</b><br>
                                    <?= Yii::t('hipanel:domain', 'each pair (domain + code) should be written with a new line') ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-12">
            <div class="box box-widget">
                <div class="box-body">
                    <?= Html::submitButton('<i class="fa fa-paper-plane"></i>&nbsp;&nbsp;' . Yii::t('hipanel:domain', 'Transfer'), ['class' => 'btn btn-success']); ?>
                </div>
            </div>
        </div>

    </div>
    <?php ActiveForm::end() ?>
<?php else : ?>
    <?= Html::beginForm(['add-to-cart-transfer']) ?>
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Yii::t('hipanel:domain', 'Starting the transfer procedure for the following domains'); ?></h3>
        </div>
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $transferDataProvider,
                'tableOptions' => [
                    'class' => 'table table-hover',
                ],
                'layout' => "{items}\n{pager}",
                'rowOptions' => function ($model) {
                    return ['class' => $model->hasErrors('password') ? 'danger' : ''];
                },
                'columns' => [
                    [
                        'attribute' => 'domain',
                        'format' => 'raw',
                        'value' => function ($model, $key, $i) {
                            /** @var Domain $model */
                            $html = Html::tag('span', Html::encode($model->domain), ['class' => 'text-bold']);
                            if (!$model->hasErrors('password')) {
                                $html .= Html::hiddenInput("DomainTransferProduct[$i][name]", $model->domain);
                            }

                            return $html;
                        },
                    ],
                    [
                        'attribute' => 'password',
                        'format' => 'raw',
                        'value' => function ($model, $key, $i) {
                            /** @var Domain $model */
                            $html = Html::encode($model->password);
                            if (!$model->hasErrors('password')) {
                                $html .= Html::hiddenInput("DomainTransferProduct[$i][password]", $model->password);
                            }

                            return $html;
                        },
                    ],
                    [
                        'label' => Yii::t('hipanel:domain', 'Additional message'),
                        'value' => function ($model) {
                            /* @var Domain $model */
                            return $model->hasErrors('password') ? $model->getFirstError('password') : '';
                        },
                    ],
                ],
            ]); ?>
        </div>
        <div class="box-footer">
            <?= Html::submitButton('<i class="fa fa-shopping-cart"></i> ' . Yii::t('hipanel:domain', 'Add to cart'), ['disabled' => $this->context->isButtonDisabled($models), 'class' => 'btn btn-success']) ?>
            <?= Html::a(Yii::t('hipanel:domain', 'Return to transfer form'), ['transfer'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
    <?= Html::endForm(); ?>
<?php endif; ?>
