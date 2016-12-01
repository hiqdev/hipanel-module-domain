<?php

use hipanel\modules\client\widgets\Verification;
use hipanel\modules\domain\models\Domain;
use hipanel\modules\domain\widgets\CheckCircle;
use yii\helpers\Html;
use yii\widgets\DetailView;

/*
 * @var $domainContactInfo array
 * @var $domainId integer
 * @var $domainName string
 */
?>

<?php foreach (Domain::contactOptionsWithLabel() as $contactType => $label) : ?>
    <?php $contact = $domainContactInfo[$contactType] ?>
    <?php $verificationModel = $contactModels[$contactType] ?>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-6"><?= Html::tag('h4', Yii::t('hipanel:domain', $label)) ?></div>
            <div class="col-md-6">
                <div class="pull-right btn-group" style="padding-top:10px">
                    <?= Html::a(Yii::t('hipanel', 'Details'), ['@contact/view', 'id' => $contact['id']], ['class' => 'btn btn-default btn-xs']) ?>
                    <?php
//                    Html::a(Yii::t('hipanel', 'Change'), [
//                        '@contact/change-contact',
//                        'contactId' => $contact['id'],
//                        'contactType' => $contactType,
//                        'domainId' => $domainId,
//                        'domainName' => $domainName,
//                    ], ['class' => 'btn btn-default btn-xs'])
                    ?>
                </div>
            </div>
        </div>
        <?= DetailView::widget([
            'model' => $contact,
            'attributes' => [
                'name:raw:' .         Yii::t('hipanel:client', 'Name') .         CheckCircle::widget(['value' => $verificationModel->getVerification('name')->isVerified()]),
                'email:email:' .      Yii::t('hipanel:client', 'Email') .        CheckCircle::widget(['value' => $verificationModel->getVerification('email')->isVerified()]),
                'organization:raw:' . Yii::t('hipanel:client', 'Organization'),
                'voice_phone:raw:' .  Yii::t('hipanel:client', 'Phone') .        CheckCircle::widget(['value' => $verificationModel->getVerification('voice_phone')->isVerified()]),
                'fax_phone:raw:' .    Yii::t('hipanel:client', 'Fax') .          CheckCircle::widget(['value' => $verificationModel->getVerification('fax_phone')->isVerified()]),
            ],
        ]) ?>
    </div>
<?php endforeach; ?>
