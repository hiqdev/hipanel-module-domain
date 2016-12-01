<?php

use hipanel\modules\client\grid\ContactGridView;
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
        <?= ContactGridView::detailView([
            'boxed' => false,
            'model' => $contact,
            'columns' => [
                'name',
                'email_v',
                'organization',
                'voice_phone',
                'fax_phone',
            ],
        ]) ?>
    </div>
<?php endforeach ?>
