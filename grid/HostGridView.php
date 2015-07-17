<?php
/**
 * @link    http://hiqdev.com/hipanel-module-domain
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\domain\grid;

use hipanel\grid\BoxedGridView;
use hipanel\grid\MainColumn;
use hipanel\modules\domain\models\Host;
use hiqdev\xeditable\grid\XEditableColumn;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

class HostGridView extends BoxedGridView
{
    static public function defaultColumns()
    {
        return [
            'host' => [
                'value' => function($model) {
                    return Html::tag('b', $model->host);
                },
                'filterAttribute' => 'host_like',
                'format' => 'html'
            ],
//            'ips' => [
//                'class'                 => EditableColumn::className(),
//                'filterAttribute'       => 'ips_like',
//                'popover'               => Yii::t('app','Up to 13 IP addresses'),
//                'action'                => ['update'],
//            ],
            'ips' => [
                'class' => XEditableColumn::className(),

                'pluginOptions' => [
                    'url' => 'update',
                ],
                'format' => 'raw'
            ]

        ];
    }
}
