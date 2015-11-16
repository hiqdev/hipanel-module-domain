<?php

/*
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2015, HiQDev (http://hiqdev.com/)
 */

/**
 * @link    http://hiqdev.com/hipanel-module-domain
 *
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */
namespace hipanel\modules\domain\controllers;

use Yii;

class HostController extends \hipanel\base\CrudController
{
    public function actions()
    {
        return [
            'index' => [
                'class'     => 'hipanel\actions\IndexAction',
            ],
            'view' => [
                'class'     => 'hipanel\actions\ViewAction',
            ],
            'validate-form' => [
                'class'     => 'hipanel\actions\ValidateFormAction',
            ],
            'create' => [
                'class'     => 'hipanel\actions\SmartCreateAction',
                'success'   => Yii::t('app', 'Name server created'),
            ],
            'update' => [
                'class'     => 'hipanel\actions\SmartUpdateAction',
                'success'   => Yii::t('app', 'Name server updated'),
            ],
            'delete' => [
                'class'     => 'hipanel\actions\SmartPerformAction',
                'success'   => Yii::t('app', 'Name server deleted'),
            ],
        ];
    }
}
