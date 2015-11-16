<?php

/*
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2015, HiQDev (http://hiqdev.com/)
 */

use hipanel\widgets\Pjax;
use yii\helpers\Html;
use yii\web\JsExpression;

Pjax::begin(array_merge(Yii::$app->params['pjax'], [
    'id' => 'domain-view-sync-button',
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
