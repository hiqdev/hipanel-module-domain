<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\menus;

use Yii;
use yii\helpers\StringHelper;

class DomainActionsMenu extends \hiqdev\yii2\menus\Menu
{
    public $model;

    public function items()
    {
        $user = Yii::$app->user;
        $url = 'http://' . $this->model->domain . '/';

        return [
            [
                'icon' => 'fa-paper-plane',
                'label' => Yii::t('hipanel:domain', 'Go to site {link}', ['link' => StringHelper::truncate($url, 15)]),
                'url' => $url,
                'encode' => false,
                'linkOptions' => [
                    'target' => '_blank',
                    'rel' => 'noopener noreferrer',
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
                'visible' => $this->model->canSendFOA() && !$this->model->canForceSendFOA(),
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
                'visible' => $this->model->canApproveTransfer(),
                'encode' => false,
                'linkOptions' => [
                    'data' => [
                        'confirm' => Yii::t('hipanel:domain', 'Are you sure you want to approve outgoing transfer of domain {domain}?', ['domain' => $this->model->domain]),
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
                'visible' => $this->model->canRejectTransfer(),
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
                'visible' => $this->model->canCancelTransfer(),
                'encode' => false,
            ],
            [
                'label' => Yii::t('hipanel:domain', 'Cancel preincoming transfer'),
                'icon' => 'fa-trash',
                'url' => ['@domain/force-reject-preincoming', 'id' => $this->model->id, 'domain' => $this->model->domain],
                'linkOptions' => [
                    'data' => [
                        'confirm' => Yii::t('hipanel:domain', 'Are you sure you want to cancel domain {domain} transfer?', ['domain' => $this->model->domain]),
                        'method' => 'post',
                        'pjax' => '0',
                        'form' => 'force-reject-preincoming',
                        'params' => [
                            'Domain[id]' => $this->model->id,
                            'Domain[domain]' => $this->model->domain,
                        ],
                    ],
                ],
                'visible' => $this->model->canCancelPreincoming(),
            ],
            [
                'label' => Yii::t('hipanel:domain', 'Approve preincoming transfer'),
                'icon' => 'fa-exclamation-circle',
                'url' => ['@domain/force-approve-preincoming', 'id' => $this->model->id, 'domain' => $this->model->domain],
                'linkOptions' => [
                    'data' => [
                        'confirm' => Yii::t('hipanel:domain', 'Are you sure you want to approve domain {domain} transfer?', ['domain' => $this->model->domain]),
                        'method' => 'post',
                        'pjax' => '0',
                        'form' => 'force-approve-preincoming',
                        'params' => [
                            'Domain[id]' => $this->model->id,
                            'Domain[domain]' => $this->model->domain,
                        ],
                    ],
                ],
                'visible' => $this->model->canCancelPreincoming(),
            ],
            [
                'label' => Yii::t('hipanel:domain', 'Synchronize contacts'),
                'icon' => 'fa-refresh',
                'url' => ['sync', 'id' => $this->model->id],
                'visible' => $this->model->canSynchronizeContacts(),
                'encode' => false,
            ],
            [
                'label' => Yii::t('hipanel:domain', 'Delete'),
                'icon' => 'fa-trash-o',
                'url' => ['@domain/delete'],
                'linkOptions' => [
                    'data' => [
                        'confirm' => Yii::t('hipanel:domain', 'Are you sure you want to delete domain {domain}?', ['domain' => $this->model->domain]),
                        'method' => 'post',
                        'data-pjax' => '0',
                        'form' => 'delete-agp',
                        'params' => [
                            'Domain[id]' => $this->model->id,
                        ],
                    ],
                ],
                'visible' => !$this->model->canDeleteAGP() && $this->model->canDelete(),
                'encode' => false,
            ],
            [
                'label' => Yii::t('hipanel:domain', 'Delete by AGP'),
                'icon' => 'fa-trash-o',
                'url' => ['@domain/delete-agp'],
                'linkOptions' => [
                    'data' => [
                        'confirm' => Yii::t('hipanel:domain', 'Are you sure you want to delete domain {domain}?', ['domain' => $this->model->domain]),
                        'method' => 'post',
                        'data-pjax' => '0',
                        'form' => 'delete-agp',
                        'params' => [
                            'Domain[id]' => $this->model->id,
                        ],
                    ],
                ],
                'visible' => $this->model->canDeleteAGP(),
                'encode' => false,
            ],
            [
                'label' => Yii::t('hipanel:domain', 'Delete in DB'),
                'icon' => 'fa-trash-o',
                'url' => ['@domain/delete-in-db'],
                'linkOptions' => [
                    'data' => [
                        'confirm' => Yii::t('hipanel:domain', 'Are you sure you want to delete domain {domain}?', ['domain' => $this->model->domain]),
                        'method' => 'post',
                        'data-pjax' => '0',
                        'form' => 'delete-agp',
                        'params' => [
                            'Domain[id]' => $this->model->id,
                        ],
                    ],
                ],
                'visible' => $user->can('domain.delete'),
                'encode' => false,
            ],
            [
                'label' => !$this->model->isFreezed()
                    ? Yii::t('hipanel:domain', 'Freeze domain')
                    : Yii::t('hipanel:domain', 'Unfreeze domain'),
                'url' => !$this->model->isFreezed()
                    ? ['@domain/enable-freeze']
                    : ['@domain/disable-freeze'],
                'icon' => 'fa-snowflake-o',
                'visible' => $this->model->canFreezeUnfreeze(),
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
                'label' => !$this->model->isWPFreezed()
                    ? Yii::t('hipanel:domain', 'Enable WHOIS-protect freeze')
                    : Yii::t('hipanel:domain', 'Disable WHOIS-protect freeze'),
                'url' => !$this->model->isWPFreezed()
                    ? ['@domain/enable-w-p-freeze']
                    : ['@domain/disable-w-p-freeze'],
                'icon' => 'fa-snowflake-o',
                'visible' => $this->model->canWPFreezeUnfreeze(),
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
                'visible' => $this->model->canHoldUnhold(),
            ],
            [
                'label' => Yii::t('hipanel:domain', 'Domain renew only in DB'),
                'url' => ['@domain/renew-in-data-base'],
                'icon' => 'fa-circle',
                'linkOptions' => [
                    'data' => [
                        'method' => 'post',
                        'pjax' => '0',
                        'form' => 'renew-in-data-base-' . $this->model->id,
                        'params' => [
                            'Domain[id]' => $this->model->id,
                            'Domain[domain]' => $this->model->domain,
                            'Domain[expires]' => date("Y-m-d", strtotime($this->model->expires)),
                        ],
                    ],
                ],
                'visible' => $user->can('domain.maintain'),
            ],
        ];
    }
}
