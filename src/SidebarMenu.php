<?php

namespace hipanel\modules\domain;

use Yii;

class SidebarMenu extends \hiqdev\menumanager\Menu
{

    protected $_addTo = 'sidebar';

    protected $_where = [
        'after'     => ['tickets', 'finance', 'clients', 'dashboard', 'header'],
        'before'    => ['servers', 'hosting'],
    ];

    public function items()
    {
        return [
            'domains' => [
                'label' => Yii::t('app', 'Domains'),
                'url'   => ['/domains/default/index'],
                'icon'  => 'fa-globe',
                'items' => [
                    'domains' => [
                        'label' => Yii::t('app', 'Domains'),
                        'url'   => ['/domain/domain/index'],
                    ],
                    'nameservers' => [
                        'label' => Yii::t('app', 'Name Servers'),
                        'url'   => ['/domain/host/index'],
                    ],
                    'contacts' => [
                        'label' => Yii::t('app', 'Contacts'),
                        'url'   => ['/client/contact/index'],
                    ],
                    'check-domain' => [
                        'label' => Yii::t('app', 'Check domain'),
                        'url'   => ['/domain/domain/check-domain'],
                    ],
//                  'seo' => [
//                      'label' => Yii::t('app', 'SEO'),
//                      'url'   => ['/domain/domain/index'],
//                  ],
                ],
            ],
        ];
    }

}
