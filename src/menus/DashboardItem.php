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

use hipanel\modules\dashboard\DashboardInterface;
use Yii;

class DashboardItem extends \hiqdev\yii2\menus\Menu
{
    protected $dashboard;

    public function __construct(DashboardInterface $dashboard, $config = [])
    {
        $this->dashboard = $dashboard;
        parent::__construct($config);
    }

    public function items()
    {
        return [
            'domains' => [
                'label' => $this->render('dashboardItem', $this->dashboard->mget(['totalCount', 'model'])),
                'encode' => false,
                'visible' => Yii::$app->user->can('domain.read'),
            ],
        ];
    }
}
