<?php

namespace hipanel\modules\domain\menus;

use hipanel\modules\domain\models\Domain;
use Yii;

class DomainActionsMenu extends \hiqdev\menumanager\Menu
{
    public $model;

    public function items()
    {
        return [
            [
                'label' => '<i class="fa fa-fw fa-info"></i> ' . Yii::t('hipanel', 'View'),
                'url' => ['@domain/view', 'id' => $this->model->id],
                'encode' => false,
            ],
            [
                'label' => '<i class="fa fa-fw fa-envelope-o"></i> ' . Yii::t('hipanel:domain', 'Send FOA again'),
                'url' => ['@domain/notify-transfer-in'],
                'linkOptions' => [
                    'data' => [
                        'method' => 'post',
                        'data-pjax' => '0',
                        'form' => 'notify-transfer-in',
                        'params' => [
                            'Domain[id]' => $this->model->id,
                        ]
                    ],
                ],
                'encode' => false,
                'visible' => $this->model->state === 'preincoming',
            ],
            [
                'label' => '<i class=""></i> ' . Yii::t('hipanel:domain', 'approve-preincoming'),
                'url' => '#',
                'visible' => false,
                'encode' => false,
            ],
            [
                'label' => '<i class=""></i> ' . Yii::t('hipanel:domain', 'reject-preincoming'),
                'url' => '#',
                'visible' => false,
                'encode' => false,
            ],
            [
                'label' => '<i class="fa fa-fw fa-exclamation-circle"></i> ' . Yii::t('hipanel:domain', 'Approve transfer'),
                'url' => ['@domain/approve-transfer'],
                'visible' => ($this->model->state === 'outgoing' && Yii::$app->user->can('support') && Domain::notDomainOwner($this->model)),
                'encode' => false,
                'linkOptions' => [
                    'data' => [
                        'confirm' => Yii::t('hipanel:domain', 'Are you sure you want to cancel incoming transfer of domain {domain}?', ['domain' => $this->model->domain]),
                        'method' => 'post',
                        'data-pjax' => '0',
                        'form' => 'approve-transfer',
                        'params' => [
                            'Domain[id]' => $this->model->id,
                        ]
                    ],
                ],
            ],
            [
                'label' => '<i class="fa fa-fw fa-anchor"></i> ' . Yii::t('hipanel:domain', 'Reject transfer'),
                'url' => ['reject-transfer', 'id' => $this->model->id],
                'visible' => $this->model->state === 'outgoing',
                'encode' => false,
            ],
            [
                'label' => '<i class="fa fa-fw fa-exclamation-triangle"></i> ' . Yii::t('hipanel:domain', 'Cancel transfer'),
                'url' => ['@domain/cancel-transfer'],
                'linkOptions' => [
                    'data' => [
                        'confirm' => Yii::t('hipanel:domain', 'Are you sure you want to cancel incoming transfer of domain {domain}?', ['domain' => $this->model->domain]),
                        'method' => 'post',
                        'data-pjax' => '0',
                        'form' => 'cancel-transfer',
                        'params' => [
                            'Domain[id]' => $this->model->id,
                        ]
                    ],
                ],
                'visible' => $this->model->state === 'incoming',
                'encode' => false,
            ],
            [
                'label' => '<i class="fa fa-fw fa-refresh"></i> ' . Yii::t('hipanel:domain', 'Synchronize contacts'),
                'url' => ['sync', 'id' => $this->model->id],
                'visible' => (in_array($this->model->state, ['ok', 'expired'], true) && Yii::$app->user->can('support') && Domain::notDomainOwner($this->model)),
                'encode' => false,
            ],
            [
                'label' => '<i class="fa fa-fw fa-trash-o"></i> ' . Yii::t('hipanel:domain', 'Delete by AGP'),
                'url' => ['@domain/delete-agp'],
                'linkOptions' => [
                    'confirm' => Yii::t('hipanel:domain', 'Are you sure you want to delete domain {domain}?', ['domain' => $this->model->domain]),
                    'method' => 'post',
                    'data-pjax' => '0',
                    'form' => 'delete-agp',
                    'params' => [
                        'Domain[id]' => $this->model->id,
                    ],
                ],
                'visible' => Yii::$app->user->can('manage') && (!(!in_array($this->model->state, ['ok'], true)) ||
                        !(time() >= strtotime('+5 days', strtotime($this->model->created_date))) ||
                        !(strtotime('+1 year', time()) < strtotime($this->model->expires)) ||
                        in_array(Domain::getZone($this->model->domain), ['com', 'net'], true)),
                'encode' => false,
            ],
            [
                'label' => '<i class="fa fa-fw fa-lock"></i> ' . Yii::t('hipanel:domain', 'Freeze domain'),
                'url' => ['@domain/enable-freeze'],
                'visible' => (!$this->model->is_freezed && Yii::$app->user->can('support') && Domain::notDomainOwner($this->model)),
                'linkOptions' => [
                    'data' => [
                        'method' => 'post',
                        'data-pjax' => '0',
                        'form' => 'freeze',
                        'params' => [
                            'Domain[id]' => $this->model->id,
                        ],
                    ],
                ],
                'encode' => false,
            ],
            [
                'label' => '<i class="fa fa-fw fa-unlock"></i> ' . Yii::t('hipanel:domain', 'Unfreeze domain'),
                'url' => ['@domain/disable-freeze'],
                'linkOptions' => [
                    'data' => [
                        'method' => 'post',
                        'data-pjax' => '0',
                        'form' => 'unfreeze',
                        'params' => [
                            'Domain[id]' => $this->model->id,
                        ],
                    ],
                ],
                'visible' => ($this->model->is_freezed && Yii::$app->user->can('support') && Domain::notDomainOwner($this->model)),
                'encode' => false,
            ],
            [
                'label' => '<i class="fa fa-fw fa-bomb"></i> ' . Yii::t('hipanel:domain', 'Enable Hold'),
                'url' => ['@domain/enable-hold'],
                'linkOptions' => [
                    'data' => [
                        'method' => 'post',
                        'data-pjax' => '0',
                        'form' => 'hold',
                        'params' => [
                            'Domain[id]' => $this->model->id,
                        ],
                    ],
                ],
                'visible' => !($this->model->is_holded) && (Yii::$app->user->can('support') && Yii::$app->user->not($this->model->client_id) && Yii::$app->user->not($this->model->seller_id)),
                'encode' => false,
            ],
            [
                'label' => '<i class="fa fa-fw fa-link"></i> ' . Yii::t('hipanel:domain', 'Disable Hold'),
                'url' => ['@domain/disable-hold'],
                'visible' => ($this->model->is_holded && in_array($this->model->state, ['ok', 'expired'], true) && Yii::$app->user->can('support') && Domain::notDomainOwner($this->model)),
                'linkOptions' => [
                    'data' => [
                        'method' => 'post',
                        'data-pjax' => '0',
                        'form' => 'unhold',
                        'params' => [
                            'Domain[id]' => $this->model->id,
                        ],
                    ],
                ],
                'encode' => false,
            ],
            [
                'label' => '<i class="fa fa-fw fa-globe"></i> ' . Yii::t('hipanel:domain', 'Manage DNS'),
                'url' => ['@dns/zone/view', 'id' => $this->model->id],
                'visible' => (Yii::getAlias('@dns', false)),
                'encode' => false,
            ],
        ];
    }
}
