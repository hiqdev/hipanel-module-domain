<?php
/**
 * @link    http://hiqdev.com/hipanel-module-domain
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\domain\grid;

use hipanel\grid\ActionColumn;
use hipanel\grid\MainColumn;
use hipanel\modules\domain\widgets\State;
use hipanel\modules\domain\widgets\Expires;
use hipanel\grid\BoxedGridView;
use hipanel\grid\RefColumn;
use hipanel\widgets\ArraySpoiler;
use hiqdev\bootstrap_switch\BootstrapSwitchColumn;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class DomainGridView extends BoxedGridView
{
    static public function defaultColumns()
    {
        return [
            'domain'          => [
                'class'     => MainColumn::className(),
                'attribute' => 'domain',
                'note'      => true,
                'filterAttribute' => 'domain_like'
            ],
            'state'           => [
                'class'         => RefColumn::className(),
                'format'        => 'raw',
                'gtype'         => 'state,domain',
                'headerOptions' => ['style' => 'width: 1em'],
                'value'         => function ($model) {
                    return State::widget(compact('model'));
                }
            ],
            'whois_protected' => [
                'class'         => BootstrapSwitchColumn::className(),
                'filter'        => false,
                'url'           => Url::toRoute('set-whois-protect'),
                'popover'       => 'WHOIS protection',
                'pluginOptions' => [
                    'onColor'  => 'success',
                    'offColor' => 'warning',
                ],
            ],
            'is_secured'      => [
                'class'     => BootstrapSwitchColumn::className(),
                'filter'    => false,
                'url'       => Url::toRoute('set-lock'),
                'attribute' => 'is_secured',
                'popover'   => Yii::t('app', 'Protection from transfer'),
            ],
            'note'            => [
                'class'         => 'hiqdev\xeditable\grid\XEditableColumn',
                'attribute'     => 'note',
                'filter'        => true,
                'popover'       => Yii::t('app', 'Make any notes for your convenience'),
                'pluginOptions' => [
                    'url' => 'set-note',
                ],
            ],
            'created_date'    => [
                'attribute'      => 'created_date',
                'format'         => 'date',
                'filter'         => false,
                'contentOptions' => ['class' => 'text-nowrap'],
            ],
            'expires'         => [
                'format'        => 'raw',
                'filter'        => false,
                'headerOptions' => ['style' => 'width:1em'],
                'value'         => function ($model) {
                    return Expires::widget(compact('model'));
                },
            ],
            'autorenewal'     => [
                'class'         => BootstrapSwitchColumn::className(),
                'label'         => Yii::t('app', 'Autorenew'),
                'filter'        => false,
                'url'           => Url::toRoute('set-autorenewal'),
                'popover'       => 'The domain will be autorenewed for one year in a week before it expires if you have enough credit on your account',
                'pluginOptions' => [
                    'onColor' => 'info',
                ],
            ],
            'nameservers'     => [
                'format' => 'raw',
                'value'  => function ($model) {
                    return ArraySpoiler::widget(['data' => $model->nameservers]);
                },
            ],
            'actions'         => [
                'class'    => ActionColumn::className(),
                'template' => '{view}', // {state}
                'header'   => Yii::t('app', 'Actions'),
            ],
        ];
    }
}
