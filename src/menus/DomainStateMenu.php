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

use hipanel\modules\domain\widgets\State;
use Yii;

class DomainStateMenu extends \hiqdev\yii2\menus\Menu
{
    public $options = [
        'style' => 'list-style:none',
    ];

    public $model;

    public function items(): array
    {
        return [
            [
                'encode' => false,
                'label' => State::widget(array_filter([
                    'model' => $this->model,
                    'labelOptions' => array_filter(['title' => $this->model->getStateTitle()]),
                ])),
            ],
            [
                'icon' => 'fa-snowflake-o',
                'label' => Yii::t('hipanel:domain', 'Frozen'),
                'options' => [
                    'class' => 'label label-info',
                ],
                'visible' => $this->model->isFreezed(),
            ],
            [
                'icon' => 'fa-snowflake-o',
                'label' => Yii::t('hipanel:domain', 'WP Frozen'),
                'options' => [
                    'class' => 'label label-info',
                ],
                'visible' => $this->model->isWPFreezed(),
            ],
            [
                'icon' => 'fa-ban',
                'label' => Yii::t('hipanel:domain', 'Held'),
                'options' => [
                    'class' => 'label label-warning',
                ],
                'visible' => $this->model->isHolded(),
            ],
        ];
    }
}
