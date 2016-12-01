<?php

use hipanel\modules\client\widgets\Verification;
use hipanel\modules\domain\models\Domain;
use hipanel\modules\domain\widgets\CheckCircle;
use yii\helpers\Html;
use yii\widgets\DetailView;

?>

<?php foreach ($model->contactOptionsWithLabel() as $contactType => $label) : ?>
    <?php $contact = $model->{$contactType} ?>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-6"><?= Html::tag('h4', $label) ?></div>
            <div class="col-md-6">
                <div class="pull-right btn-group" style="padding-top:10px">
                    <?= Html::a(Yii::t('hipanel', 'Details'), ['@contact/view', 'id' => $contact['id']], ['class' => 'btn btn-default btn-xs']) ?>
                    <?php
//                    Html::a(Yii::t('hipanel', 'Change'), [
//                        '@contact/change-contact',
//                        'contactId' => $contact['id'],
//                        'contactType' => $contactType,
//                        'domainId' => $model->id,
//                        'domainName' => $model->domain,
//                    ], ['class' => 'btn btn-default btn-xs'])
                    ?>
                </div>
            </div>
        </div>
        <?= DetailView::widget([
            'model' => $contact,
            'attributes' => [
                'name:raw:' .         Yii::t('hipanel:client', 'Name') .         CheckCircle::widget(['value' => $contact->getVerification('name')->isVerified()]),
                'email:email:' .      Yii::t('hipanel:client', 'Email') .        CheckCircle::widget(['value' => $contact->getVerification('email')->isVerified()]),
                'organization:raw:' . Yii::t('hipanel:client', 'Organization'),
                'voice_phone:raw:' .  Yii::t('hipanel:client', 'Phone') .        CheckCircle::widget(['value' => $contact->getVerification('voice_phone')->isVerified()]),
                'fax_phone:raw:' .    Yii::t('hipanel:client', 'Fax') .          CheckCircle::widget(['value' => $contact->getVerification('fax_phone')->isVerified()]),
            ],
        ]) ?>
    </div>
<?php endforeach ?>
