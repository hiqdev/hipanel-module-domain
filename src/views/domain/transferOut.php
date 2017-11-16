<?php

use hipanel\modules\domain\grid\DomainGridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('hipanel:domain', 'Domain transfer: {domain}', ['domain' => strtoupper($model->domain)]);
$this->params['subtitle'] = Yii::t('hipanel:domain', 'outgoing transfer confirmation');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel', 'Domains'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('.hover-item {  white-space: normal; } .hover-item:hover { filter: brightness(95%);}');

?>

<div class="shared-table">
    <div class="row" style="font-size:110%">
        <div class="col-md-8 col-md-offset-2">
            <div class="box box-widget">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?= DomainGridView::detailView([
                                'boxed' => false,
                                'model' => $model,
                                'columns' => [
                                    'transfer_attention',
                                    'transfer_re',
                                ],
                            ]) ?>
                        </div>
                        <div class="col-md-12" style="margin: 1em 0;">
                            <p class="bg-warning" style="padding:1em;">
                                <?= Yii::t('hipanel:domain', 'We have received notification on {request_date} that you have requested a transfer to another domain name registrar.', [
                                    'request_date' => Yii::$app->formatter->asDate($model->request_date),
                                ]) ?>
                                <?= Yii::t('hipanel:domain', 'If you wish to cancel the transfer, please press the button below.') ?>
                            </p>
                        </div>
                        <div class="col-md-12">
                            <?php $form = ActiveForm::begin([
                                'id' => 'reject-transfer-form',
                                'method' => 'POST',
                                'action' => Url::to(['@domain/reject-transfer']),
                            ]) ?>
                            <?= Html::activeHiddenInput($model, 'id') ?>
                            <?= Html::submitButton(
                                '<b>' . Yii::t('hipanel:domain', 'I REJECT.') . ' ' .
                                Yii::t('hipanel:domain', 'Please cancel the transfer my domain: {domain}.', ['domain' => $model->domain])
                                , ['class' => 'btn btn-danger btn-block btn-lg hover-item']) ?>
                            <?php ActiveForm::end() ?>
                            <p class="text-muted text-center bg-danger" style="padding: 1em;">
                                <?= Yii::t('hipanel:domain', 'I am one of the contacts currently listed for the domain and I have the authority to reject this request.') ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <p class="text-muted text-center bg-success" style="padding: 1em; margin-bottom: 0;">
                        <?= Yii::t('hipanel:domain', 'If you wish to proceed, please back to domain info.') ?>
                        <br/>
                        <?= Yii::t('hipanel:domain', 'Transfer will be approved automatically.') ?>
                    </p>
                    <?= Html::a('<b>' . Yii::t('hipanel:domain', 'I APPROVE.') . ' ' . Yii::t('hipanel:domain', 'Back to domain info'), [
                        '@domain/view',
                        'id' => $model->id,
                    ], ['class' => 'btn btn-success btn-block hover-item']) ?>
                </div>
                <div class="box-footer">
                    <p class="text-muted text-center"><?= Yii::t('hipanel:domain', 'If you have any further questions, please {create_a_ticket}.', ['create_a_ticket' => Html::a(Yii::t('hipanel:domain', 'create a ticket'), ['@ticket/create'])]) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
