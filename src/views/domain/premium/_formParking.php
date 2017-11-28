<?php

use hipanel\modules\domain\widgets\UsePremiumFeaturesButton;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

if (!$model->isNewRecord) {
    $model->park_id = $model->id;
}
$model->domain_id = $domain->id;
$this->registerJs(<<<JS
$("#parking-type_id").select2({
    allowClear: true,
    width: "100%",
    templateResult: function(option) {
        if (!option.id || option.id == 0) { 
            return option.text; 
        }
        return '<img src="https://ahnames.com/www/img/parking/park'+ option.id + '.png" width="50" />&nbsp;&nbsp;' + option.text;
    },
    templateSelection: function (option) {
        if (option.id.length > 0 ) {
            return '<img src="https://ahnames.com/www/img/parking/park'+ option.id + '.png" width="20" />&nbsp;&nbsp;' + option.text;
        } else {
            return option.text;
        }
    },
    escapeMarkup: function (m) {
        return m;
    }
});
JS
);

?>

<?php $form = ActiveForm::begin([
    'id' => 'park-form-' . ($model->id ?: time()),
    'action' => Url::to(['@domain/set-premium-feature', 'for' => 'parking']),
    'enableAjaxValidation' => true,
    'options' => [
        'data-pjax' => true,
        'data-pjaxPush' => false,
    ],
    'validationUrl' => Url::toRoute(['@domain/validate-park-form', 'scenario' => $model->scenario]),
]) ?>

<?= Html::activeHiddenInput($model, "domain_id") ?>
<?php if (!$model->isNewRecord) : ?>
    <?= Html::activeHiddenInput($model, "id") ?>
    <?= Html::activeHiddenInput($model, "park_id") ?>
<?php endif; ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'title') ?>
            <?= $form->field($model, 'siteheader') ?>
            <?= $form->field($model, 'type_id')->dropDownList($model->skinOptions()) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'sitetext')->textarea(['rows' => 7]) ?>
        </div>
        <div class="col-md-12">
            <?php if ((bool)$domain->premium->is_active === false) : ?>
                <?= UsePremiumFeaturesButton::widget([
                    'text' => Yii::t('hipanel:domain', 'Save'),
                    'options' => ['class' => 'btn btn-success btn-sm'],
                ]) ?>
            <?php else : ?>
                <?= Html::submitButton(Yii::t('hipanel:domain', 'Save'), ['class' => 'btn btn-success btn-sm']) ?>
            <?php endif; ?>
        </div>
    </div>
<?php $form->end();
