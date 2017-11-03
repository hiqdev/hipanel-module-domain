<?php

/** @var string $domains */

/** @var string $till_date */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('hipanel:domain', 'Domain transfer approval');
$this->params['subtitle'] = Yii::t('hipanel:domain', 'incoming transfer confirmation');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel', 'Domains'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row" style="font-size:110%">
    <div class="col-md-8">
        <div class="box box-widget">
            <div class="box-body">
                <p class="text-bold">
                    <?= Html::encode(strtoupper($domains)) ?>
                </p>
                <p><?= Yii::t('hipanel:domain', 'Please read the following important information about transferring your domain name:') ?></p>
                <ul>
                    <li>
                        <?= Yii::t('hipanel:domain', 'By approving the transfer you enter into a new Registration Agreement with us. Please {review}.', ['review' => Html::a(Yii::t('hipanel:domain', 'review the full terms and conditions of the Agreement'), 'https://ahnames.com/rules', ['target' => '_blank'])]) ?>
                    </li>
                    <li>
                        <?= Yii::t('hipanel:domain', 'Once you approved the transfer, the transfer will take place within six (6) calendar days unless the current registrar of record denies the request.') ?>
                    </li>
                    <li>
                        <?= Yii::t('hipanel:domain', 'Once a transfer takes place, you will not be able to transfer to another registrar for 60 days, apart from a transfer back to the original registrar, in cases where both registrars so agree or where a decision in the dispute resolution process so directs.') ?>
                    </li>
                </ul>
                <p>
                    <?= Yii::t('hipanel:domain', 'More information on domain transfer process can be found on {ican} site and especially in {pilicy_on_transfer_of_registrations}.', [
                        'ican' => Html::a('ICAN', 'https://www.icann.org/resources/pages/registrars/transfers-en', ['target' => '_blank']),
                        'pilicy_on_transfer_of_registrations' => Html::a(Yii::t('hipanel:domain', 'Policy on Transfer of Registrations between Registrars'), 'http://www.icann.org/ru/resources/registrars/transfers/policy', ['target' => '_blank']),
                    ]) ?>
                </p>
            </div>
            <div class="box-footer text-center">
                <?= Html::tag('h3', Yii::t('hipanel:domain', 'To proceed with the transfer you must respond with one of the following:')) ?>
            </div>
            <div class="box-footer">
                <?php $form = ActiveForm::begin([
                    'id' => 'reject-transfer-form',
                    'method' => 'POST',
                    'action' => Url::to('@domain/approve-transfer'),
                ]) ?>
                <?= Html::submitButton(
                    '<b>' . Yii::t('hipanel:domain', 'I APPROVE.') . '</b><br>' .
                    Yii::t('hipanel:domain', 'Please cancel the transfer my domain(s): {domains}.', ['domains' => '<br>' . $domains])
                    , ['class' => 'btn btn-success btn-block btn-lg']) ?>
                <?php ActiveForm::end() ?>
                <p class="text-muted text-center bg-success" style="padding: 1em;">
                    <?= Yii::t('hipanel:domain', 'I am one of the contacts currently listed for the domain and I have the authority to approve this request.') ?>
                    <?= Yii::t('hipanel:domain', 'By approving I agree to {rules}.', ['rules' => Html::a(Yii::t('hipanel:domain', 'the terms and conditions'), '#')]) ?>
                </p>
            </div>
            <div class="box-footer">
                <?php $form = ActiveForm::begin([
                    'id' => 'reject-transfer-form',
                    'method' => 'POST',
                    'action' => Url::to('@domain/approve-transfer'),
                ]) ?>
                <?= Html::submitButton(
                    '<b>' . Yii::t('hipanel:domain', 'I REJECT.') . '</b><br>' .
                    Yii::t('hipanel:domain', 'I am one of the contacts currently listed for the domain and I have the authority to reject this request.')
                    , ['class' => 'btn btn-danger btn-block']) ?>
                <?php ActiveForm::end() ?>
                <p class="text-center bg-danger text-muted" style="padding: 1em;">
                    <?= Yii::t('hipanel:domain', 'ATTENTION: If you do not respond by {till_date}, domains: {domains} will not be transferred to us.', [
                        'till_date' => Yii::$app->formatter->asDate($till_date),
                        'domains' => strtoupper($domains),
                    ]) ?>
                </p>
            </div>
            <div class="box-footer">
                <p class="text-muted text-center"><?= Yii::t('hipanel:domain', 'If you have any further questions, please {create_a_ticket}.', ['create_a_ticket' => Html::a(Yii::t('hipanel:domain', 'create a ticket'), ['@ticket/create'])]) ?></p>
            </div>
        </div>
    </div>
</div>
