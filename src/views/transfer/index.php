<?php

use hipanel\modules\domain\cart\DomainTransferProduct;
use hipanel\modules\domain\models\Domain;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = Yii::t('hipanel/domainchecker', 'Domain transfer');
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
            <div class="box box-solid">
                <div class="box-body">
                    <?= Html::submitButton('<i class="fa fa-paper-plane"></i>&nbsp;&nbsp;' . Yii::t('hipanel/domainchecker', 'Transfer'), ['class' => 'btn btn-success']); ?>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#single"
                                          data-toggle="tab"><?= Yii::t('hipanel/domainchecker', 'Domain transfer') ?></a></li>
                    <li><a href="#bulk" data-toggle="tab"><?= Yii::t('hipanel/domainchecker', 'Bulk domain transfer') ?></a></li>

                </ul>

                <div class="tab-content" style="padding-top: 1em">

                    <div class="tab-pane active" id="single">
                        <div class="row">
                            <div class="col-md-1 step">1.</div>
                            <!-- /.col-md-1 -->
                            <div
                                class="col-md-11"><?= Yii::t('hipanel/domainchecker', 'Remove WHOIS protection from the current registrar.') ?></div>
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
                                <?= Yii::t('hipanel/domainchecker', 'An email was sent to your email address specified in Whois. To start the transfer, click on the link in the email.') ?>
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
                                    <?= Yii::t('hipanel/domainchecker', 'For separation of the domain and code use a space, a comma or a semicolon.') ?>
                                    <?= Yii::t('hipanel/domainchecker', 'Example') ?>:<br>
                                    <b>yourdomain.com uGt6shlad</b><br>
                                    <?= Yii::t('hipanel/domainchecker', 'or') ?><br>
                                    <b>yourdomain.com, uGt6shlad</b><br>
                                    <?= Yii::t('hipanel/domainchecker', 'or') ?><br>
                                    <b>yourdomain.com; uGt6shlad</b><br>
                                    <?= Yii::t('hipanel/domainchecker', 'each pair (domain + code) should be written with a new line') ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <?php ActiveForm::end() ?>
<?php else : ?>
    <?= Html::beginForm(['add-to-cart-transfer']) ?>
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Yii::t('hipanel/domainchecker', 'Starting the transfer procedure for the following domains'); ?></h3>
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
                            $html = Html::tag('span', $model->domain, ['class' => 'text-bold']);
                            /** @var Domain $model */
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
                            $html = $model->password;
                            /** @var Domain $model */
                            if (!$model->hasErrors('password')) {
                                $html .= Html::hiddenInput("DomainTransferProduct[$i][password]", $model->password);
                            }

                            return $html;
                        },
                    ],
                    [
                        'label' => Yii::t('hipanel/domainchecker', 'Additional message'),
                        'value' => function ($model) {
                            /* @var Domain $model */
                            return $model->hasErrors('password') ? $model->getFirstError('password') : '';
                        },
                    ],
                ],
            ]); ?>
        </div>
        <div class="box-footer">
            <?= Html::submitButton('<i class="fa fa-shopping-cart"></i> ' . Yii::t('hipanel/domainchecker', 'Add to cart'), ['class' => 'btn btn-success']) ?>
            <?= Html::a(Yii::t('hipanel/domainchecker', 'Return to transfer form'), ['transfer'], ['class' => 'btn btn-default']) ?>
        </div>
        <!-- /.box-footer -->
    </div>
    <?= Html::endForm(); ?>
<?php endif; ?>
