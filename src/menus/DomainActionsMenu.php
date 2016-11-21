<?php

namespace hipanel\modules\domain\menus;

use hipanel\modules\domain\models\Domain;
use Yii;

class DomainActionsMenu extends \hiqdev\menumanager\Menu
{
    public $model;

    public function items()
    {
        $url = 'http://' . $this->model->domain . '/';

        return [
            [
                'icon' => 'fa-paper-plane',
                'label' => Yii::t('hipanel:domain', 'Go to site {link}', ['link' => \yii\helpers\StringHelper::truncate($url, 15)]),
                'url' => $url,
                'encode' => false,
                'linkOptions' => [
                    'target' => '_blank',
                ],
            ],
            'view' => [
                'label' => Yii::t('hipanel', 'View'),
                'icon' => 'fa-info',
                'url' => ['@domain/view', 'id' => $this->model->id],
                'encode' => false,
            ],
            [
                'label' => Yii::t('hipanel:domain', 'Send FOA again'),
                'icon' => 'fa-envelope-o',
                'url' => ['@domain/notify-transfer-in'],
                'linkOptions' => [
                    'data' => [
                        'method' => 'post',
                        'pjax' => '0',
                        'form' => 'notify-transfer-in',
                        'params' => [
                            'Domain[id]' => $this->model->id,
                        ]
                    ],
                ],
                'encode' => false,
                'visible' => $this->model->state === Domain::STATE_PREINCOMING,
            ],
            [
                'label' => Yii::t('hipanel:domain', 'approve-preincoming'),
                'url' => '#',
                'visible' => false,
                'encode' => false,
            ],
            [
                'label' => Yii::t('hipanel:domain', 'reject-preincoming'),
                'url' => '#',
                'visible' => false,
                'encode' => false,
            ],
            [
                'label' => Yii::t('hipanel:domain', 'Approve transfer'),
                'icon' => 'fa-exclamation-circle',
                'url' => ['@domain/approve-transfer'],
                'visible' => ($this->model->state === Domain::STATE_OUTGOING && Yii::$app->user->can('support') && Domain::notDomainOwner($this->model)),
                'encode' => false,
                'linkOptions' => [
                    'data' => [
                        'confirm' => Yii::t('hipanel:domain', 'Are you sure you want to cancel incoming transfer of domain {domain}?', ['domain' => $this->model->domain]),
                        'method' => 'post',
                        'pjax' => '0',
                        'form' => 'approve-transfer',
                        'params' => [
                            'Domain[id]' => $this->model->id,
                        ]
                    ],
                ],
            ],
            [
                'label' => Yii::t('hipanel:domain', 'Reject transfer'),
                'icon' => 'fa-anchor',
                'url' => ['reject-transfer', 'id' => $this->model->id],
                'visible' => $this->model->state === Domain::STATE_OUTGOING,
                'encode' => false,
            ],
            [
                'label' => Yii::t('hipanel:domain', 'Cancel transfer'),
                'icon' => 'fa-exclamation-triangle',
                'url' => ['@domain/cancel-transfer'],
                'linkOptions' => [
                    'data' => [
                        'confirm' => Yii::t('hipanel:domain', 'Are you sure you want to cancel incoming transfer of domain {domain}?', ['domain' => $this->model->domain]),
                        'method' => 'post',
                        'pjax' => '0',
                        'form' => 'cancel-transfer',
                        'params' => [
                            'Domain[id]' => $this->model->id,
                        ]
                    ],
                ],
                'visible' => $this->model->state === Domain::STATE_INCOMING,
                'encode' => false,
            ],
            [
                'label' => Yii::t('hipanel:domain', 'Synchronize contacts'),
                'icon' => 'fa-refresh',
                'url' => ['sync', 'id' => $this->model->id],
                'visible' => (!in_array(Domain::getZone($this->model->domain), ['ру', 'су', 'рф'], true) && in_array($this->model->state, [Domain::STATE_OK, Domain::STATE_EXPIRED], true) && Yii::$app->user->can('support') && Domain::notDomainOwner($this->model)),
                'encode' => false,
            ],
            [
                'label' => Yii::t('hipanel:domain', 'Delete by AGP'),
                'icon' => 'fa-trash-o',
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
                'visible' =>
                    Yii::$app->user->can('manage')
                    &&
                    in_array($this->model->state, [Domain::STATE_OK], true)
                    &&
                    time() <= strtotime('+5 days', strtotime($this->model->created_date))
                    &&
                    strtotime('+1 year', time()) > strtotime($this->model->expires)
                    &&
                    in_array(Domain::getZone($this->model->domain), ['com', 'net'], true)
                ,
                'encode' => false,
            ],
            [
                'label' => Yii::t('hipanel:domain', 'Freeze domain'),
                'icon' => 'fa-lock',
                'url' => ['@domain/enable-freeze'],
                'visible' => (!$this->model->is_freezed && Yii::$app->user->can('support') && Domain::notDomainOwner($this->model)),
                'linkOptions' => [
                    'data' => [
                        'method' => 'post',
                        'pjax' => '0',
                        'form' => 'freeze',
                        'params' => [
                            'Domain[id]' => $this->model->id,
                        ],
                    ],
                ],
                'encode' => false,
            ],
            [
                'label' => Yii::t('hipanel:domain', 'Unfreeze domain'),
                'icon' => 'fa-unlock',
                'url' => ['@domain/disable-freeze'],
                'linkOptions' => [
                    'data' => [
                        'method' => 'post',
                        'pjax' => '0',
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
                'label' => Yii::t('hipanel:domain', 'Enable Hold'),
                'icon' => 'fa-ban',
                'url' => ['@domain/enable-hold'],
                'linkOptions' => [
                    'data' => [
                        'method' => 'post',
                        'pjax' => '0',
                        'form' => 'hold',
                        'params' => [
                            'Domain[id]' => $this->model->id,
                        ],
                    ],
                ],
                'visible' => !in_array(Domain::getZone($this->model->domain), ['ру', 'су', 'рф'], true) && !($this->model->is_holded) && (Yii::$app->user->can('support') && Yii::$app->user->not($this->model->client_id) && Yii::$app->user->not($this->model->seller_id)),
                'encode' => false,
            ],
            [
                'label' => Yii::t('hipanel:domain', 'Disable Hold'),
                'icon' => 'fa-link',
                'url' => ['@domain/disable-hold'],
                'visible' => !in_array(Domain::getZone($this->model->domain), ['ру', 'су', 'рф'], true) && ($this->model->is_holded && in_array($this->model->state, [Domain::STATE_OK, Domain::STATE_EXPIRED], true) && Yii::$app->user->can('support') && Domain::notDomainOwner($this->model)),
                'linkOptions' => [
                    'data' => [
                        'method' => 'post',
                        'pjax' => '0',
                        'form' => 'unhold',
                        'params' => [
                            'Domain[id]' => $this->model->id,
                        ],
                    ],
                ],
                'encode' => false,
            ],
            [
                'label' => Yii::t('hipanel:domain', 'Manage DNS'),
                'icon' => 'fa-globe',
                'url' => ['@dns/zone/view', 'id' => $this->model->id],
                'visible' => (Yii::getAlias('@dns', false)),
                'encode' => false,
            ],
        ];
    }
}
