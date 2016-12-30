<?php

use hipanel\modules\client\grid\ContactGridView;
use yii\helpers\Html;

?>

<?php foreach ($model->contactTypesWithLabels() as $contactType => $label) : ?>
    <?php $contact = $model->{$contactType} ?>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-6"><?= Html::tag('h4', $label) ?></div>
            <div class="col-md-6">
                <div class="pull-right btn-group" style="padding-top:10px">
                    <?= Html::a(Yii::t('hipanel', 'Details'), ['@contact/view', 'id' => $contact['id']], ['class' => 'btn btn-default btn-xs']) ?>
                </div>
            </div>
        </div>
        <?= ContactGridView::detailView([
            'boxed' => false,
            'model' => $contact,
            'columns' => [
                'name_link_with_verification',
                'email_link_with_verification',
                'organization',
                'voice_phone',
                'fax_phone',
            ],
        ]) ?>
    </div>
<?php endforeach ?>
