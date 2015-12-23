<?php
use hipanel\helpers\Url;
use hipanel\modules\client\widgets\combo\ContactCombo;
use hipanel\modules\domain\models\Domain;
use hipanel\widgets\ArraySpoiler;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>
<?php $form = ActiveForm::begin([
    'id' => 'bulk-set-contact-form',
    'action' => Url::toRoute('bulk-set-contacts'),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-set-contacts-form', 'scenario' => 'bulk-set-contacts']),
]) ?>

<div class="panel panel-default">
    <div class="panel-heading"><?= Yii::t('app', 'Affected domains') ?></div>
    <div class="panel-body">
        <?= ArraySpoiler::widget([
            'data' => $models,
            'visibleCount' => count($models),
            'formatter' => function ($model) {
                return $model->domain;
            },
            'delimiter' => ',&nbsp; '
        ]); ?>
    </div>
</div>

<?php foreach ($models as $item) : ?>
    <?= Html::activeHiddenInput($item, "[$item->id]id") ?>
    <?= Html::activeHiddenInput($item, "[$item->id]domain") ?>
<?php endforeach; ?>

<div class="row">
    <?php foreach (Domain::$contactOptions as $contact) : ?>
        <div class="col-sm-6">
            <?= $form->field($model, $contact)->widget(ContactCombo::classname(), [
                'hasId' => true,
                'inputOptions' => [
                    'id' => 'domain-' . $contact,
                    'name' => $contact
                ]
            ]); ?>
        </div>
    <?php endforeach; ?>
</div>

<hr>
<?= Html::submitButton('Send', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end() ?>