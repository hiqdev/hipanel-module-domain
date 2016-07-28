<?php

use hipanel\modules\domain\models\Domain;
use yii\helpers\Html;
use yii\widgets\DetailView;

/*
 * @var $domainContactInfo array
 * @var $domainId integer
 * @var $domainName string
 */
?>

<?php foreach (Domain::$contactOptions as $contactType) : ?>
    <?php $contact = $domainContactInfo[$contactType] ?>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-6"><?= Html::tag('h4', Yii::t('hipanel/client', ucfirst($contactType) . ' contact')) ?></div>
            <div class="col-md-6">
                <div class="pull-right btn-group" style="padding-top:10px">
                    <?= Html::a(Yii::t('hipanel', 'Details'), ['@contact/view', 'id' => $contact['id']], ['class' => 'btn btn-default btn-xs']) ?>
                    <?= Html::a(Yii::t('hipanel', 'Change'), [
                        '@contact/change-contact',
                        'contactId' => $contact['id'],
                        'contactType' => $contactType,
                        'domainId' => $domainId,
                        'domainName' => $domainName,
                    ], ['class' => 'btn btn-default btn-xs']) ?>
                </div>
            </div>
        </div>
        <?= DetailView::widget([
            'model' => $contact,
            'attributes' => [
                'name:raw:' .           Yii::t('hipanel/client', 'Name'),
                'email:email:' .        Yii::t('hipanel/client', 'Email'),
                'organization:raw:' .   Yii::t('hipanel/client', 'Organization'),
                'voice_phone:raw:' .    Yii::t('hipanel/client', 'Phone'),
                'fax_phone:raw:' .      Yii::t('hipanel/client', 'Fax'),
            ],
        ]) ?>
    </div>
<?php endforeach; ?>
