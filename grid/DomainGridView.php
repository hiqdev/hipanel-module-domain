<?php
/**
 * @link    http://hiqdev.com/hipanel-module-domain
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\domain\grid;

use hipanel\grid\ActionColumn;
use hipanel\modules\domain\widgets\State;
use hipanel\modules\domain\widgets\Expires;
use hipanel\grid\BoxedGridView;
use hipanel\grid\RefColumn;
use hipanel\widgets\ArraySpoiler;
use hiqdev\bootstrap_switch\BootstrapSwitchColumn;
use hiqdev\xeditable\widgets\XEditable;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
//use hipanel\grid\SwitchColumn;

class DomainGridView extends BoxedGridView
{
    static public function defaultColumns()
    {
        return [
            'domain' => [
                'attribute' => 'domain',
                'format' => 'raw',
                'value' => function ($model, $key, $index) {
                    $domainLink = Html::tag('b', Html::a($model->domain, ['view', 'id' => $key]));
                    $note = '<br>' . Html::tag('span', Yii::t('app', 'Note') . ': ', ['class' => 'bold']) . XEditable::widget([
                            'model' => $model,
                            'attribute' => 'note',
                            'pluginOptions' => [
                                'emptytext' => Yii::t('app', 'set note'),
                                'url' => Url::to('set-note')
                            ]
                        ]);
                    return $domainLink . $note;
                }
//                'class'                 => MainColumn::className(),
            ],
            'state' => [
                'class' => RefColumn::className(),
                'format' => 'raw',
                'gtype' => 'state,domain',
                'value' => function ($model) {
                    return State::widget(compact('model'));
                }
            ],
            'whois_protected' => [
                'class' => BootstrapSwitchColumn::className(),
                'filter' => false,
                'url' => Url::toRoute('set-whois-protect'),
                'popover' => 'WHOIS protection',
                'pluginOptions' => [
                    'onColor' => 'success',
                    'offColor' => 'warning',
                ],
            ],
            'is_secured' => [
//                'class' => SwitchColumn::className(),
                'class' => BootstrapSwitchColumn::className(),
                'filter' => false,
                'url' => Url::toRoute('set-lock'),
                'attribute' => 'is_secured',
                'popover' => Yii::t('app', 'Protection from transfer'),
            ],
//            'note' => [
//                'class'                 => EditableColumn::className(),
//                'attribute'             => 'note',
//                'filter'                => true,
//                'popover'               => Yii::t('app','Make any notes for your convenience'),
//                'action'                => ['set-note'],
//            ],
            'created_date' => [
                'attribute' => 'created_date',
                'format' => 'date',
                'filter' => false,
                'contentOptions' => ['class' => 'text-nowrap'],
            ],
            'expires' => [
                'format' => 'raw',
                'filter' => false,
                'headerOptions' => ['style' => 'width:1em'],
                'value' => function ($model) {
                    return Expires::widget(compact('model'));
                },
            ],
            'autorenewal' => [
                'class' => BootstrapSwitchColumn::className(),
                'label' => 'Autorenew',
                'filter' => false,
                'url' => Url::toRoute('set-autorenewal'),
                'popover' => 'The domain will be autorenewed for one year in a week before it expires if you have enough credit on your account',
                'pluginOptions' => [
                    'onColor' => 'info',
                ],
            ],
            'nameservers' => [
                'format' => 'raw',
                'value' => function ($model) {
                    return ArraySpoiler::widget(['data' => $model->nameservers]);
                },
            ],
            'actions' => [
                'class' => ActionColumn::className(),
                'template' => '{view} {block} {delete} {update}', // {state}
                'header' => Yii::t('app', 'Actions'),
                'buttons' => [
                    'block' => function ($url, $model, $key) {
                        return Html::a('Close', ['block', 'id' => $model->id]);
                    },
                ],
            ],
        ];
    }
}
