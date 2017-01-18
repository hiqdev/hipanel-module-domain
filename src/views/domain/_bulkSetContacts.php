<?php

use hipanel\helpers\Url;
use hipanel\modules\client\widgets\combo\ContactCombo;
use hipanel\modules\domain\models\Domain;
use hipanel\widgets\ArraySpoiler;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$unchangeableZones = [];
$model->setScenario(isset($domainContact) ? 'set-contacts' : 'bulk-set-contacts');
$i = 0;
?>
<?php $form = ActiveForm::begin([
    'id' => 'bulk-set-contact-form',
    'action' => Url::toRoute('bulk-set-contacts'),
    'enableAjaxValidation' => true,
]) ?>

<div class="panel panel-default">
    <div class="panel-heading"><?= Yii::t('hipanel:domain', 'Affected domains') ?></div>
    <div class="panel-body">
        <?= ArraySpoiler::widget([
            'data' => $models,
            'visibleCount' => count($models),
            'formatter' => function ($model) use (&$unchangeableZones) {
                if (!$model->isContactChangeable()) {
                    $unchangeableZones[] = $model->domain;
                }
                return $model->domain;
            },
            'delimiter' => ',&nbsp; ',
        ]) ?>
    </div>
</div>

<?php if (!empty($unchangeableZones)) : ?>
    <div class="panel panel-warning">
        <div class="panel-heading">
            <?= Yii::t('hipanel:domain', 'Selected domains contain zones which can not be changed contact details:') ?>
        </div>
        <div class="panel-body">
            <?= implode(', ', $unchangeableZones) ?>
        </div>
    </div>
<?php endif ?>

<?php foreach ($models as $item) : ?>
    <?php if ($item->isContactChangeable()) : ?>
        <?= Html::activeHiddenInput($item, "[$item->id]id") ?>
        <?= Html::activeHiddenInput($item, "[$item->id]domain") ?>
    <?php endif ?>
<?php endforeach ?>

<div class="row">
    <?php foreach (Domain::$contactTypes as $type) : ?>
        <div class="col-sm-6">
            <?php
            if (isset($domainContact) && is_array($domainContact)) {
                $model->{$type . '_id'} = $domainContact[$type];
            }
            echo $form->field($model, '[0]' . $type . '_id')->widget(ContactCombo::class, [
                'hasId' => true,
                'inputOptions' => [
                    'id' => 'domain-0-' . $type . '_id',
                    'name' => $type . '_id',
                ],
                'filter' => [
                    'client_id_in' => ['format' => ArrayHelper::getColumn($models, 'client_id')],
                ],
            ])
            ?>
        </div>
        <?php
        if ($i % 2) {
            echo Html::tag('span', null, ['class' => 'clearfix']);
        }
        ++$i;
        ?>
    <?php endforeach ?>
</div>

<hr>
<?= Html::submitButton(Yii::t('hipanel', 'Save'), ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end() ?>
