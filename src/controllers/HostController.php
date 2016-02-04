<?php

/*
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\controllers;

use hipanel\actions\IndexAction;
use hipanel\actions\SmartCreateAction;
use hipanel\actions\SmartPerformAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\actions\ValidateFormAction;
use hipanel\actions\ViewAction;
use Yii;

class HostController extends \hipanel\base\CrudController
{
    public function actions()
    {
        return [
            'index' => [
                'class'     => IndexAction::class,
            ],
            'view' => [
                'class'     => ViewAction::class,
            ],
            'validate-form' => [
                'class'     => ValidateFormAction::class,
            ],
            'create' => [
                'class'     => SmartCreateAction::class,
                'success'   => Yii::t('app', 'Name server created'),
            ],
            'update' => [
                'class'     => SmartUpdateAction::class,
                'success'   => Yii::t('app', 'Name server updated'),
            ],
            'delete' => [
                'class'     => SmartPerformAction::class,
                'success'   => Yii::t('app', 'Name server deleted'),
            ],
        ];
    }
}
