<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\grid;

use hipanel\grid\ActionColumn;
use hipanel\grid\BoxedGridView;
use hipanel\grid\MainColumn;
use hipanel\grid\XEditableColumn;
use hipanel\modules\domain\menus\DomainActionsMenu;
use hipanel\modules\domain\models\Domain;
use hipanel\modules\domain\widgets\Expires;
use hipanel\modules\domain\widgets\GetPremiumButton;
use hipanel\modules\domain\widgets\State;
use hipanel\widgets\ArraySpoiler;
use hipanel\widgets\Label;
use hiqdev\bootstrap_switch\BootstrapSwitchColumn;
use hiqdev\bootstrap_switch\LabeledAjaxSwitch;
use hiqdev\combo\StaticCombo;
use hiqdev\yii2\menus\grid\MenuColumn;
use hiqdev\yii2\menus\widgets\Menu;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class DomainGridView extends BoxedGridView
{
    public function columns()
    {
        return array_merge(parent::columns(), [
            'is_premium' => [
                'format' => 'raw',
                'value' => function ($model) {
                    $state = ($model->premium->is_active) ? Yii::t('hipanel:domain', 'Activated to {expires,date} ({days_left,plural,=0{# days} =1{# day} other{# days}} left)', [
                        'expires' => strtotime($model->premium->expires),
                        'days_left' => $model->premium->days_left,
                    ]) : Html::tag('span', Yii::t('hipanel:domain', 'Not activated'), ['class' => 'text-danger']);

                    return $state . GetPremiumButton::widget(['model' => $model]);
                },
                'contentOptions' => [
                    'style' => 'display: flex; flex-direction: row; justify-content: space-between; flex-wrap: wrap;',
                ],
            ],
            'premium_autorenewal' => [
                'class' => BootstrapSwitchColumn::class,
                'attribute' => 'premium_autorenewal',
                'url' => Url::toRoute('@hdomain/set-premium-autorenewal'),
                'filter' => false,
                'enableSorting' => false,
                'encodeLabel' => false,
                'label' => Html::tag('span', Yii::t('hipanel:domain', 'Premium autorenewal')),
                'pluginOptions' => function ($model) {
                    return [
                        'readonly' => !(bool)$model->premium->is_active,
                    ];
                },
                'switchOptions' => [
                    'class' => LabeledAjaxSwitch::class,
                    'labels' => [
                        0 => [
                            'style' => 'display: none;',
                            'class' => 'text-danger md-pl-10',
                            'content' => Yii::t('hipanel:domain', 'You can enable the automatic renewal of the premium package for this domain.'),
                        ],
                        1 => [
                            'style' => 'display: none;',
                            'class' => 'small text-muted font-normal md-pl-10',
                            'content' => Yii::t('hipanel:domain', 'Automatic renewal of premium package for this domain is enabled.'),
                        ],
                    ],
                ],

            ],
            'transfer_attention' => [
                'label' => Yii::t('hipanel:domain', 'Attention'),
                'value' => function ($model) {
                    return $model->client_name;
                },
            ],
            'transfer_re' => [
                'attribute' => 'domain',
                'label' => Yii::t('hipanel:domain', 'Re'),
                'value' => function ($model) {
                    return Yii::t('hipanel:domain', 'Transfer of {domain}', ['domain' => strtoupper($model->domain)]);
                },
            ],
            'domain' => [
                'class' => MainColumn::class,
                'attribute' => 'domain',
                'note' => true,
                'filterAttribute' => 'domain_like',
            ],
            'state' => [
                'format' => 'html',
                'filter' => function ($grid, $model, $attribute) {
                    return StaticCombo::widget([
                        'model' => $model,
                        'attribute' => $attribute,
                        'data' => Domain::stateOptions(),
                        'hasId' => true,
                    ]);
                },
                'filterInputOptions' => ['style' => 'width:120px'],
                'enableSorting' => false,
                'value' => function ($model) {
                    $labelOptions = $model->labelTitle ? ['title' => $model->labelTitle] : [];
                    $out = State::widget(compact('model', 'labelOptions'));
                    $status = [];
                    if ($model->is_freezed || $model->is_holded || $model->wp_freezed) {
                        $out .= '<br>';
                        $status[] = $model->is_freezed ? Html::tag('span', Html::tag('span', '', ['class' => Menu::iconClass('fa-snowflake-o')]) . ' ' . Yii::t('hipanel:domain', 'Froze'), ['class' => 'label label-info']) : '';
                        $status[] = $model->wp_freezed ? Html::tag('span', Html::tag('span', '', ['class' => Menu::iconClass('fa-snowflake-o')]) . ' ' . Yii::t('hipanel:domain', 'WP Froze'), ['class' => 'label label-info']) : '';
                        $status[] = $model->is_holded ? Html::tag('span', Html::tag('span', '', ['class' => Menu::iconClass('fa-ban')]) . ' ' . Yii::t('hipanel:domain', 'Held'), ['class' => 'label label-warning']) : '';
                    }

                    return $out . implode('&nbsp;', $status);
                },
            ],
            'foa_sent_to' => [
                'format' => 'html',
                'filter' => false,
                'visible' => function ($model) {
                    return $model->state === Domain::STATE_PREINCOMING;
                },
                'value' => function ($model) {
                    return Html::tag('span', '', ['class' => Menu::iconClass('fa-envelope')]) . ' ' . $model->foa_sent_to;
                },
            ],
            'whois_protected' => [ // don't forget to update `whois_protected_with_label` column as well
                'attribute' => 'whois_protected',
                'filter' => false,
                'enableSorting' => false,
                'encodeLabel' => false,
                'label' => Html::tag('span', 'WHOIS'),
                'popover' => 'WHOIS protection',
                'popoverOptions' => [
                    'placement' => 'bottom',
                    'selector' => 'span',
                ],
                'format' => 'html',
                'value' => function ($model) {
                    return $this->getStateLabel($model->whois_protected);
                },
            ],
            'whois_protected_with_label' => [ // don't forget to update `whois_protected` column as well
                'class' => BootstrapSwitchColumn::class,
                'attribute' => 'whois_protected',
                'url' => Url::toRoute('set-whois-protect'),
                'filter' => false,
                'enableSorting' => false,
                'encodeLabel' => false,
                'label' => Html::tag('span', 'WHOIS'),
                'popover' => 'WHOIS protection',
                'popoverOptions' => [
                    'placement' => 'bottom',
                    'selector' => 'span',
                ],
                'pluginOptions' => [
                    'offColor' => 'warning',
                ],
                'switchOptions' => [
                    'class' => LabeledAjaxSwitch::class,
                    'labels' => [
                        0 => [
                            'style' => 'display: none;',
                            'class' => 'text-danger md-pl-10',
                            'content' => Yii::t('hipanel:domain', 'The contact data is visible to everybody in the Internet'),
                        ],
                        1 => [
                            'style' => 'display: none;',
                            'class' => 'small text-muted font-normal md-pl-10',
                            'content' => Yii::t('hipanel:domain', 'The contact data is protected and not exposed'),
                        ],
                    ],
                ],
            ],
            'is_secured' => [ // don't forget to update `is_secured_with_label` column as well
                'encodeLabel' => false,
                'filter' => false,
                'enableSorting' => false,
                'label' => Html::tag('span', Yii::t('hipanel:domain', 'Protection')),
                'attribute' => 'is_secured',
                'popover' => Yii::t('hipanel:domain', 'Protection from transfer'),
                'popoverOptions' => [
                    'placement' => 'bottom',
                    'selector' => 'span',
                ],
                'format' => 'html',
                'value' => function ($model) {
                    return $this->getStateLabel($model->is_secured);
                },
            ],
            'is_secured_with_label' => [ // don't forget to update `is_secured` column as well
                'class' => BootstrapSwitchColumn::class,
                'encodeLabel' => false,
                'filter' => false,
                'enableSorting' => false,
                'label' => Html::tag('span', Yii::t('hipanel:domain', 'Protection')),
                'url' => Url::toRoute('set-lock'),
                'attribute' => 'is_secured',
                'popover' => Yii::t('hipanel:domain', 'Protection from transfer'),
                'popoverOptions' => [
                    'placement' => 'bottom',
                    'selector' => 'span',
                ],
                'switchOptions' => [
                    'class' => LabeledAjaxSwitch::class,
                    'labels' => [
                        0 => [
                            'style' => 'display: none;',
                            'class' => 'text-danger md-pl-10',
                            'content' => Yii::t('hipanel:domain', 'The domain can be transferred, edited or deleted'),
                        ],
                        1 => [
                            'style' => 'display: none;',
                            'class' => 'small text-muted font-normal md-pl-10',
                            'content' => Yii::t('hipanel:domain', 'The domain can not be transferred, edited or deleted'),
                        ],
                    ],
                ],
            ],
            'note' => [
                'class' => XEditableColumn::class,
                'attribute' => 'note',
                'filter' => true,
                'popover' => Yii::t('hipanel:domain', 'Make any notes for your convenience'),
                'pluginOptions' => [
                    'url' => 'set-note',
                ],
            ],
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
            'autorenewal' => [ // don't forget to update `autorenewal_with_label` column as well
                'label' => Html::tag('span', Yii::t('hipanel', 'Autorenew')),
                'attribute' => 'autorenewal',
                'format' => 'html',
                'value' => function ($model) {
                    return $this->getStateLabel($model->autorenewal);
                },
                'filter' => false,
                'enableSorting' => false,
                'encodeLabel' => false,
                'popover' => Yii::t('hipanel:domain', 'The domain will be autorenewed for one year in a week before it expires if you have enough credit on your account'),
                'popoverOptions' => [
                    'placement' => 'bottom',
                    'selector' => 'span',
                ],
            ],
            'autorenewal_with_label' => [ // don't forget to update `autorenewal` column as well
                'class' => BootstrapSwitchColumn::class,
                'filter' => false,
                'url' => Url::toRoute('set-autorenewal'),
                'attribute' => 'autorenewal',
                'label' => Html::tag('span', Yii::t('hipanel', 'Autorenew')),
                'enableSorting' => false,
                'encodeLabel' => false,
                'popover' => Yii::t('hipanel:domain', 'The domain will be autorenewed for one year in a week before it expires if you have enough credit on your account'),
                'popoverOptions' => [
                    'placement' => 'bottom',
                    'selector' => 'span',
                ],
                'switchOptions' => [
                    'class' => LabeledAjaxSwitch::class,
                    'labels' => [
                        0 => [
                            'style' => 'display: none;',
                            'class' => 'text-danger md-pl-10',
                            'content' => Yii::t('hipanel:domain', 'The domain will not be renewed automatically'),
                        ],
                        1 => [
                            'style' => 'display: none;',
                            'class' => 'small text-muted font-normal md-pl-10',
                            'content' => Yii::t('hipanel:domain', 'The domain will be renewed automatically if balance is sufficient'),
                        ],
                    ],
                ],
            ],
            'nameservers' => [
                'format' => 'raw',
                'value' => function ($model) {
                    return ArraySpoiler::widget(['data' => $model->nameservers]);
                },
            ],
            'actions' => [
                'class' => MenuColumn::class,
                'menuClass' => DomainActionsMenu::class,
            ],
            'old_actions' => [
                'class' => ActionColumn::class,
                'template' => '{view} {manage-dns} {notify-transfer-in} {approve-preincoming} {reject-preincoming} {approve-transfer} {reject-transfer} {cancel-transfer} {sync} {enable-hold} {disable-hold} {enable-freeze} {disable-freeze} {delete-agp} {delete}',
                // {state}
                'header' => Yii::t('hipanel', 'Actions'),
                'buttons' => [
                    'notify-transfer-in' => function ($url, $model, $key) {
                        return $model->state === 'preincoming'
                            ? Html::a('<i class="fa fa-envelope-o"></i>' . Yii::t('hipanel:domain', 'Send FOA again'), $url, [
                                'data' => [
                                    'method' => 'post',
                                    'data-pjax' => '0',
                                ],
                            ]) : '';
                    },
                    'approve-preincoming' => function ($url, $model, $key) {
                    },
                    'reject-preincoming' => function ($url, $model, $key) {
                    },
                    'approve-transfer' => function ($url, $model, $key) {
                        return ($model->state === 'outgoing' && Yii::$app->user->can('support') && Domain::notDomainOwner($model))
                            ? Html::a('<i class="fa fa-exclamation-circle"></i>' . Yii::t('hipanel:domain', 'Approve transfer'), $url, [
                                'data' => [
                                    'confirm' => Yii::t('hipanel:domain', 'Are you sure you want to approve outgoing transfer of domain {domain}?', ['domain' => $model->domain]),
                                    'method' => 'post',
                                    'data-pjax' => '0',
                                ],
                            ]) : '';
                    },
                    'reject-transfer' => function ($url, $model, $key) {
                        return $model->state === 'outgoing'
                            ? Html::a('<i class="fa fa-anchor"></i>' . Yii::t('hipanel:domain', 'Reject transfer'), $url, [
                                'data' => [
                                    'confirm' => Yii::t('hipanel:domain', 'Are you sure you want to reject outgoing transfer of domain {domain}?', ['domain' => $model->domain]),
                                    'method' => 'post',
                                    'data-pjax' => '0',
                                ],
                            ]) : '';
                    },
                    'cancel-transfer' => function ($url, $model, $key) {
                        return $model->state === 'incoming'
                            ? Html::a('<i class="fa fa-exclamation-triangle"></i>' . Yii::t('hipanel:domain', 'Cancel transfer'), $url, [
                                'data' => [
                                    'confirm' => Yii::t('hipanel:domain', 'Are you sure you want to cancel incoming transfer of domain {domain}?', ['domain' => $model->domain]),
                                    'method' => 'post',
                                    'data-pjax' => '0',
                                ],
                            ]) : '';
                    },
                    'sync' => function ($url, $model, $key) {
                        return (in_array($model->state, [
                                'ok',
                                'expired',
                            ], true) && Yii::$app->user->can('support') && Domain::notDomainOwner($model))
                            ? Html::a('<i class="fa ion-ios-loop-strong"></i>' . Yii::t('hipanel:domain', 'Synchronize contacts'), $url, [
                                'data' => [
                                    'method' => 'post',
                                    'data-pjax' => '0',
                                ],
                            ]) : '';
                    },
                    'delete' => function ($url, $model, $key) {
                        return $model->isDeleteble() ? Html::a('<i class="fa fa-trash-o"></i>' . Yii::t('hipanel', 'Delete'), $url, [
                            'title' => Yii::t('hipanel', 'Delete'),
                            'aria-label' => Yii::t('hipanel', 'Delete'),
                            'data' => [
                                'confirm' => Yii::t('hipanel:domain', 'Are you sure you want to delete domain {domain}?', ['domain' => $model->domain]),
                                'method' => 'post',
                                'data-pjax' => '0',
                            ],
                        ]) : '';
                    },
                    'delete-agp' => function ($url, $model, $key) {
                        if (!in_array($model->state, ['ok'], true)) {
                            return '';
                        }
                        if (time() >= strtotime('+5 days', strtotime($model->created_date))) {
                            return '';
                        }
                        if (strtotime('+1 year', time()) < strtotime($model->expires)) {
                            return '';
                        }

                        return in_array(Domain::getZone($model->domain), ['com', 'net'], true)
                            ? Html::a('<i class="fa fa-trash-o"></i>' . Yii::t('hipanel:domain', 'Delete by AGP'), $url, [
                                'title' => Yii::t('hipanel:domain', 'Delete by AGP'),
                                'aria-label' => Yii::t('hipanel:domain', 'Delete by AGP'),
                                'data' => [
                                    'confirm' => Yii::t('hipanel:domain', 'Are you sure you want to delete domain {domain}?', ['domain' => $model->domain]),
                                    'method' => 'post',
                                    'data-pjax' => '0',
                                ],
                            ]) : '';
                    },
                    'enable-freeze' => function ($url, $model, $key) {
                        return (!$model->is_freezed && Yii::$app->user->can('support') && Domain::notDomainOwner($model))
                            ? Html::a('<i class="fa fa-lock"></i>' . Yii::t('hipanel:domain', 'Freeze domain'), $url, [
                                'data' => [
                                    'method' => 'post',
                                    'data-pjax' => '0',
                                ],
                            ]) : '';
                    },
                    'disable-freeze' => function ($url, $model, $key) {
                        return ($model->is_freezed && Yii::$app->user->can('support') && Domain::notDomainOwner($model))
                            ? Html::a('<i class="fa fa-unlock"></i>' . Yii::t('hipanel:domain', 'Unfreeze domain'), $url, [
                                'data' => [
                                    'method' => 'post',
                                    'data-pjax' => '0',
                                ],
                            ]) : '';
                    },
                    'enable-hold' => function ($url, $model, $key) {
                        if ($model->is_holded) {
                            return '';
                        }

                        if (Yii::$app->user->can('support') && Yii::$app->user->not($model->client_id) && Yii::$app->user->not($model->seller_id)) {
                            return Html::a('<i class="fa fa-bomb"></i>' . Yii::t('hipanel:domain', 'Enable Hold'), $url);
                        }

                        return '';
                    },
                    'disable-hold' => function ($url, $model, $key) {
                        return ($model->is_holded && in_array($model->state, [
                                'ok',
                                'expired',
                            ], true) && Yii::$app->user->can('support') && Domain::notDomainOwner($model))
                            ? Html::a('<i class="fa fa-link"></i>' . Yii::t('hipanel:domain', 'Disable Hold'), $url, [
                                'data' => [
                                    'method' => 'post',
                                    'data-pjax' => '0',
                                ],
                            ]) : '';
                    },
                    'manage-dns' => function ($url, $model, $key) {
                        if (Yii::getAlias('@dns', false)) {
                            return Html::a('<i class="fa fa-globe"></i>' . Yii::t('hipanel:domain', 'Manage DNS'), [
                                '@dns/zone/view',
                                'id' => $model->id,
                            ]);
                        }

                        return '';
                    },
                ],
            ],
        ]);
    }

    protected function getStateLabel($value)
    {
        return Label::widget([
            'label' => $value ? Yii::t('hipanel', 'Enabled') : Yii::t('hipanel', 'Disabled'),
            'color' => $value ? 'success' : null,
        ]);
    }
}
