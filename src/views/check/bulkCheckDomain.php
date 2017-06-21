<?php

use hipanel\helpers\Url;
use hiqdev\assets\icheck\iCheckAsset;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$this->title = Yii::t('hipanel:domain', 'Bulk search');
$this->params['breadcrumbs'][] = $this->title;

iCheckAsset::register($this);
$this->registerJs("jQuery('input').iCheck({ checkboxClass: 'icheckbox_minimal-blue', radioClass: 'iradio_minimal' });");
$this->registerCss('
    .zones { -webkit-column-count: 3; -moz-column-count: 3; column-count: 3; }
    .zones .checkbox { margin-top: 0; }
');

?>

<?php $form = ActiveForm::begin([
    'id' => 'check-domain',
    'action' => Url::to(['/domain/check/check-domain']),
    'method' => 'get',
    'options' => [
        'data-pjax' => false,
    ],
    'fieldConfig' => [
        'template' => "{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
    ],
]) ?>
<div class="row">
    <div class="col-md-6">
        <div class="box box-widget">
            <div class="box-body">
                <p>
                    <?= Yii::t('hipanel:domain', 'You can add domains indicating the specific area, as well as a word, selecting the necessary zones afterwards. Use a space, comma, semicolon or newline for word separation.') ?>
                </p>
                <?= $form->field($model, 'fqdn')->textarea(['placeholder' => Yii::t('hipanel:domain', 'Bulk search'), 'rows' => 10])
                    ->hint(Yii::t('hipanel:domain', 'Bulk search allows you to check multiple domains for availability.')); ?>
                <div class="zones">
                    <?= $form->field($model, 'zone')->checkboxList($zones); ?>
                </div>
            </div>
            <div class="box-footer">
                <?= Html::submitButton(Yii::t('hipanel', 'Search'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>

    </div>
</div>
<?php ActiveForm::end() ?>
