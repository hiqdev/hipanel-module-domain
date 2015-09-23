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
                'template' => '{view} {notify-transfer-in} {approve-preincoming} {reject-preincoming} {approve-transfer} {reject-transfer} {cancel-transfer} {sync} {enable-hold} {disable-hold} {enable-freeze} {disable-freeze} {delete-agp} {delete}', // {state}
                'header'   => Yii::t('app', 'Actions'),
                'buttons'  => [
                    'notify-transfer-in' => function($url, $model, $key) {
                        return $model->state == 'preincoming'
                            ? Html::a('<i class="fa fa-envelope-o"></i>' . Yii::t('app', 'Send FOA again'), $url, [
                                'data' => [
                                    'method'  => 'post',
                                    'data-pjax' => '0',
                                ],
                            ]) : '';
                    },
                    'approve-preincoming' => function($url, $model, $key) {
                    },
                    'reject-preincoming' => function($url, $model, $key) {
                    },
                    'approve-transfer' => function($url, $model, $key) {
                        return ($model->state == 'outgoing' && Yii::$app->user->can('support') && Domain::notDomainOwner($model))
                            ? Html::a('<i class="fa fa-exclamation-circle"></i>' . Yii::t('app', 'Approve transfer'), $url, [
                                'data' => [
                                    'confirm' => Yii::t('app', 'Are you sure you want to approve outgoing transfer of domain {domain}?', ['domain' => $model->domain]),
                                    'method'  => 'post',
                                    'data-pjax' => '0',
                                ],
                            ]) : '';
                    },
                    'reject-transfer' => function($url, $model, $key) {
                        return $model->state == 'outgoing'
                            ? Html::a('<i class="fa fa-anchor"></i>' . Yii::t('app', 'Reject transfer'), $url, [
                                'data' => [
                                    'confirm' => Yii::t('app', 'Are you sure you want to reject outgoing transfer of domain {domain}?', ['domain' => $model->domain]),
                                    'method'  => 'post',
                                    'data-pjax' => '0',
                                ],
                            ]) : '';
                    },
                    'cancel-transfer' => function($url, $model, $key) {
                        return $model->state == 'incoming'
                            ? Html::a('<i class="fa fa-exclamation-triangle"></i>' . Yii::t('app', 'Cancel transfer'), $url, [
                                'data' => [
                                    'confirm' => Yii::t('app', 'Are you sure you want to cancel incoming transfer of domain {domain}?', ['domain' => $model->domain]),
                                    'method'  => 'post',
                                    'data-pjax' => '0',
                                ],
                            ]) : '';
                    },
                    'sync'          => function($url, $model, $key) {
                        return (in_array($model->state, ['ok', 'expired']) && Yii::$app->user->can('support') && Domain::notDomainOwner($model))
                            ? Html::a('<i class="fa ion-ios-loop-strong"></i>' . Yii::t('app', 'Synchronize contacts'), $url, [
                                'data' => [
                                    'method'  => 'post',
                                    'data-pjax' => '0',
                                ],
                            ]) : '';
                    },
                    'delete'        => function($url, $model, $key) {
                        return in_array($model->state, ['ok', 'expired', 'outgoing']) ? Html::a('<i class="fa fa-trash-o"></i>' . Yii::t('yii', 'Delete'), $url, [
                                'title'        => Yii::t('yii', 'Delete'),
                                'aria-label'   => Yii::t('yii', 'Delete'),
                                'data' => [
                                    'confirm' => Yii::t('app', 'Are you sure you want to delete domain {domain}?', ['domain' => $model->domain]),
                                    'method'  => 'post',
                                    'data-pjax' => '0',
                                ],
                        ]) : '';
                    },
                    'delete-agp'    => function($url, $model, $key) {
                        if (!in_array($model->state, ['ok'])) return '';
                        if (time() >= strtotime('+5 days', strtotime($model->created_date))) return '';
                        if (strtotime('+1 year', time()) < strtotime($model->expires)) return '';
                        return in_array(Domain::getZone($model->domain), ['com', 'net'])
                            ? Html::a('<i class="fa fa-trash-o"></i>' . Yii::t('app', 'Delete by AGP'), $url, [
                                'title'        => Yii::t('app', 'Delete by AGP'),
                                'aria-label'   => Yii::t('app', 'Delete by AGP'),
                                'data' => [
                                    'confirm' => Yii::t('app', 'Are you sure you want to delete domain {domain}?', ['domain' => $model->domain]),
                                    'method'  => 'post',
                                    'data-pjax' => '0',
                                ],
                            ]) : '';
                    },
                    'enable-freeze' => function($url, $model, $key) {
                        return (!$model->is_freezed && Yii::$app->user->can('support') && Domain::notDomainOwner($model))
                            ? Html::a('<i class="fa fa-lock"></i>' . Yii::t('app', 'Freeze domain'), $url, [
                                'data' => [
                                    'method'  => 'post',
                                    'data-pjax' => '0',
                                ],
                            ]) : '';
                    },
                    'disable-freeze'=> function($url, $model, $key) {
                        return ($model->is_freezed && Yii::$app->user->can('support') && Domain::notDomainOwner($model))
                            ? Html::a('<i class="fa fa-unlock"></i>' . Yii::t('app', 'Unfreeze domain'), $url, [
                                'data' => [
                                    'method'  => 'post',
                                    'data-pjax' => '0',
                                ],
                            ]) : '';
                    },
                    'enable-hold'   => function($url, $model, $key) {
                        if ($model->is_holded) return '';
                        if (Yii::$app->user->can('support') && Yii::$app->user->not($model->client_id) && Yii::$app->user->not($model->seller_id)) {
                            return Html::a('<i class="fa fa-bomb"></i>' . Yii::t('app', 'Enable Hold'), $url);
                            return '';
                        }
                    },
                    'disable-hold'  => function($url, $model, $key) {
                        return ($model->is_holded && in_array($model->state, ['ok', 'expired']) && Yii::$app->user->can('support') && Domain::notDomainOwner($model))
                            ? Html::a('<i class="fa fa-link"></i>' . Yii::t('app', 'Disable Hold'), $url, [
                                'data' => [
                                    'method'  => 'post',
                                    'data-pjax' => '0',
                                ],
                            ]) : '';
                    },
                ],
            ],
        ];
    }
}
