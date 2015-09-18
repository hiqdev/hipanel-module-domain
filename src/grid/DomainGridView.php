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
use hipanel\modules\domain\models\Domain;

class DomainGridView extends BoxedGridView
{
    static public function defaultColumns()
    {
        return [
            'domain'          => [
                'class'           => MainColumn::className(),
                'attribute'       => 'domain',
                'note'            => true,
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
                'url'           => Url::toRoute('set-note'),
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
                'template' => '{view} {delete-agp} {delete} {enable-lock} {disable-lock} {enable-freeze} {disable-freeze} {enable-hold} {disable-hold}', // {state}
                'header'   => Yii::t('app', 'Actions'),
                'buttons'  => [
                    'delete-agp'    => function($url, $model, $key) {
                        if (time() >= strtotime('+5 days', strtotime($model->created_date))) return '';
                        if (strtotime('+1 year', time()) < strtotime($model->expires)) return '';
                        return in_array(Domain::getZone($model->domain), ['com', 'net'])
                            ? Html::a('<i class="fa fa-trash-o"></i>' . Yii::t('app', 'Delete by AGP'), $url) : '';
                    },
                    'enable-lock'   => function($url, $model, $key) {
                        if (Yii::$app->user->can('support') && Yii::$app->user->not($model->client_id) && Yii::$app->user->not($model->seller_id)) {
                        } else {
                            return '';
                        }
                    },
                    'disable-lock'  => function($url, $model, $key) {
                        if (Yii::$app->user->can('support') && Yii::$app->user->not($model->client_id) && Yii::$app->user->not($model->seller_id)) {
                        } else {
                            return '';
                        }

                    },
                    'enable-freeze' => function($url, $model, $key) {
                        if ($model->is_freezed) return '';
                        if (Yii::$app->user->can('support') && Yii::$app->user->not($model->client_id) && Yii::$app->user->not($model->seller_id)) {
                            return Html::a('<i class="fa fa-anchor"></i>' . Yii::t('app', 'Freeze domain'), $url);
                        } else {
                            return '';
                        }
                    },
                    'disable-freeze'=> function($url, $model, $key) {
                        if (!$model->is_freezed) return '';
                        if (Yii::$app->user->can('support') && Yii::$app->user->not($model->client_id) && Yii::$app->user->not($model->seller_id)) {
                            return Html::a('<i class="fa fa-unlock-alt"></i>' . Yii::t('app', 'Unfreeze domain'), $url);
                        } else {
                            return '';
                        }
                    },
                    'enable-hold'   => function($url, $model, $key, $class) {
                        if ($model->is_holded) return '';
                        if (Yii::$app->user->can('support') && Yii::$app->user->not($model->client_id) && Yii::$app->user->not($model->seller_id)) {
                            return Html::a('<i class="fa fa-bomb"></i>' . Yii::t('app', 'Enable Hold'), $url);
                            return '';
                        }
                    },
                    'disable-hold'  => function($url, $model, $key) {
                        if (!$model->is_holded) return '';
                        if (Yii::$app->user->can('support') && Yii::$app->user->not($model->client_id) && Yii::$app->user->not($model->seller_id)) {
                            return Html::a('<i class="fa fa-bolt"></i>' . Yii::t('app', 'Disable Hold'), $url);
                        } else {
                            return '';
                        }
                    },
                ],
            ],
        ];
    }
}
