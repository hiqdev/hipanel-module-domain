<?php

use yii\helpers\Html;
use hipanel\widgets\Pjax;
use yii\helpers\Url;
use yii\web\JsExpression;

Pjax::begin(array_merge(Yii::$app->params['pjax'], [
    'enablePushState' => false,
    'clientOptions'   => [
        'type' => 'POST',
        'data' => ["{$model->formName()}[id]" => $model->id],
    ],
]));

echo Html::a(
    '<i class="ion-ios-loop-strong"></i>' . Yii::t('app', 'Synchronize contacts'),
    ['sync', 'id' => $model->id],
    ['onClick' => new JsExpression("$(this).html('<i class=\"ion-ios-loop-strong\">" . Yii::t('app', 'Loading...') . "');")]
);

Pjax::end();
