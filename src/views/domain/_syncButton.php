<?php

/*
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

use hipanel\widgets\Pjax;
use yii\helpers\Html;
use yii\web\JsExpression;

Pjax::begin(array_merge(Yii::$app->params['pjax'], ['id' => 'domain-view-sync-button']));

echo Html::a(
    '<i class="fa fa-fw fa-refresh"></i>&nbsp;' . Yii::t('hipanel/domain', 'Synchronize contacts'),
    ['sync', 'id' => $model->id],
    [
        'id' => 'test123',
        'data' => [
            'pjax' => true,
            'method' => 'post',
            'params' => ["{$model->formName()}[id]" => $model->id],
            'pjax-push-state' => false,
            'pjax-container' => '#domain-view-sync-button',
            'pjax-skip-outer-containers' => true,
        ],
        'onClick' => new JsExpression("$(this).html('<i class=\"fa fa-fw fa-refresh\">&nbsp;" . Yii::t('hipanel', 'loading...') . "');"),
    ]
);

Pjax::end();
