<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\menus;

use hipanel\modules\domain\models\Domain;
use Yii;
use yii\helpers\StringHelper;

class DomainActionsMenu extends \hiqdev\yii2\menus\Menu
{
    public $model;

    public function items()
    {
        $url = 'http://' . $this->model->domain . '/';

        return [
            [
                'icon' => 'fa-paper-plane',
                'label' => Yii::t('hipanel:domain', 'Go to site {link}', ['link' => StringHelper::truncate($url, 15)]),
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
                        ],
                    ],
                ],
                'encode' => false,
                'visible' => !$this->model->isRussianZones() && $this->model->state === Domain::STATE_PREINCOMING,
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
                'visible' => ($this->model->state === Domain::STATE_OUTGOING && Yii::$app->user->can('support') && Domain::notDomainOwner($this->model)) && !$this->model->isRussianZones(),
                'encode' => false,
                'linkOptions' => [
                    'data' => [
                        'confirm' => Yii::t('hipanel:domain', 'Are you sure you want to cancel incoming transfer of domain {domain}?', ['domain' => $this->model->domain]),
                        'method' => 'post',
                        'pjax' => '0',
                        'form' => 'approve-transfer',
                        'params' => [
                            'Domain[id]' => $this->model->id,
                        ],
                    ],
                ],
            ],
            [
                'label' => Yii::t('hipanel:domain', 'Reject transfer'),
                'icon' => 'fa-anchor',
                'url' => ['reject-transfer', 'id' => $this->model->id],
                'visible' => $this->model->state === Domain::STATE_OUTGOING && !$this->model->isRussianZones(),
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
                        ],
                    ],
                ],
                'visible' => $this->model->state === Domain::STATE_INCOMING && !$this->model->isRussianZones(),
                'encode' => false,
            ],
            [
                'label' => Yii::t('hipanel:domain', 'Cancel preincoming transfer'),
                'icon' => 'fa-trash',
                'url' => ['@domain/force-reject-preincoming', 'id' => $this->model->id],
                'linkOptions' => [
                    'data' => [
                        'confirm' => Yii::t('hipanel:domain', 'Are you sure you want to cancel domain {domain} transfer?', ['domain' => $this->model->domain]),
                        'method' => 'post',
                        'pjax' => '0',
                    ],
                ],
                'visible' => !$this->model->isRussianZones() && $this->model->state === Domain::STATE_PREINCOMING && Yii::$app->user->can('support') && Domain::notDomainOwner($this->model),
            ],
            [
                'label' => Yii::t('hipanel:domain', 'Synchronize contacts'),
                'icon' => 'fa-refresh',
                'url' => ['sync', 'id' => $this->model->id],
                'visible' => ($this->model->isSynchronizable() && in_array($this->model->state, [Domain::STATE_OK, Domain::STATE_EXPIRED], true) && Yii::$app->user->can('support') && Domain::notDomainOwner($this->model)),
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
                'visible' => $this->model->isDeletebleAGP(),
                'encode' => false,
            ],
            [
                'label' => !$this->model->isFreezed() ? Yii::t('hipanel:domain', 'Freeze domain') : Yii::t('hipanel:domain', 'Unfreeze domain'),
                'url' => !$this->model->isFreezed() ? ['@domain/enable-freeze'] : ['@domain/disable-freeze'],
                'icon' => 'fa-snowflake-o',
                'visible' => !$this->model->isRussianZones() && Yii::$app->user->can('support') && Domain::notDomainOwner($this->model),
                'linkOptions' => [
                    'data' => [
                        'method' => 'post',
                        'pjax' => '0',
                        'form' => 'freeze-' . $this->model->id,
                        'params' => [
                            'Domain[id]' => $this->model->id,
                        ],
                    ],
                ],
            ],
            [
                'label' => !$this->model->isWPFreezed() ? Yii::t('hipanel:domain', 'Enable WHOIS-protect freeze') : Yii::t('hipanel:domain', 'Disable WHOIS-protect freeze'),
                'url' => !$this->model->isWPFreezed() ? ['enable-w-p-freeze'] : ['disable-w-p-freeze'],
                'icon' => 'fa-snowflake-o',
                'visible' => !$this->model->isRussianZones() && Yii::$app->user->can('support') && Domain::notDomainOwner($this->model),
                'linkOptions' => [
                    'data' => [
                        'method' => 'post',
                        'pjax' => '0',
                        'form' => 'freeze-w-p-' . $this->model->id,
                        'params' => [
                            'Domain[id]' => $this->model->id,
                        ],
                    ],
                ],
            ],
            [
                'label' => !$this->model->isHolded() ? Yii::t('hipanel:domain', 'Enable Hold') : Yii::t('hipanel:domain', 'Disable Hold'),
                'url' => !$this->model->isHolded() ? ['@domain/enable-hold'] : ['@domain/disable-hold'],
                'icon' => !$this->model->isHolded() ? 'fa-ban' : 'fa-link',
                'linkOptions' => [
                    'data' => [
                        'method' => 'post',
                        'pjax' => '0',
                        'form' => 'hold-' . $this->model->id,
                        'params' => [
                            'Domain[id]' => $this->model->id,
                        ],
                    ],
                ],
                'visible' => !$this->model->isRussianZones() && Domain::notDomainOwner($this->model) && in_array($this->model->state,[Domain::STATE_OK, Domain::STATE_EXPIRED], true),
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
