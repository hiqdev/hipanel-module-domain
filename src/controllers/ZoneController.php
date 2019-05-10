<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\controllers;

use hipanel\actions\IndexAction;
use hipanel\actions\SmartCreateAction;
use hipanel\actions\SmartDeleteAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\actions\ValidateFormAction;
use hipanel\base\CrudController;
use Yii;
use yii\web\Response;
use hipanel\actions\ViewAction;

class ZoneController extends CrudController
{
//    /**
//     * {@inheritdoc}
//     */
//    public function behaviors(): array
//    {
//        return array_merge(parent::behaviors(), [
//            [
//                'class' => EasyAccessControl::class,
//                'actions' => [
//                    'get-zones' => 'domain.read',
//                ],
//            ],
//        ]);
//    }

    public function actions(): array
    {
        return array_merge(parent::actions(), [
            'index' => [
                'class' => IndexAction::class,
            ],
            'create' => [
                'class' => SmartCreateAction::class,
                'success' => Yii::t('hipanel:domain', 'Zone has been created'),
            ],
            'update' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel:domain', 'Zone has been updated'),
            ],
            'view' => [
                'class' => ViewAction::class,
            ],
            'delete' => [
                'class' => SmartDeleteAction::class,
                'success' => Yii::t('hipanel:domain', 'Zone has been deleted'),
            ],
//            'disable' => [
//                'class' => SmartPerformAction::class,
//            ],
//            'enable' => [
//                'class' => SmartPerformAction::class,
//            ],
            'validate-form' => [
                'class' => ValidateFormAction::class,
            ],
        ]);
    }

//    public function actionGetZones()
//    {
//        $response = Yii::$app->response;
//        $request = Yii::$app->request;
//        $user = Yii::$app->user->identity;
//        $response->format = Response::FORMAT_JSON;
//        $search = $request->post('search');
//        $models = [];
//        $apiData = Yii::$app->cache->getOrSet(['get-zones-data', $user->id], function () {
//            return Yii::$app->hiart->createCommand()->perform('get-zones', '')->getData();
//        }, 3600 * 60);
//        foreach ($apiData as $id => $zone) {
//            if (empty($search)) {
//                $models[] = ['id' => $id, 'text' => $zone];
//            } elseif (mb_stripos($zone, $search) !== false) {
//                $models[] = ['id' => $id, 'text' => $zone];
//            }
//        }
//
//        return $models;
//    }
}
