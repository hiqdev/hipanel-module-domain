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
use hipanel\actions\SmartPerformAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\actions\ValidateFormAction;
use hipanel\base\CrudController;
use hipanel\filters\EasyAccessControl;
use hipanel\modules\domain\models\Zone;
use Yii;
use hipanel\actions\ViewAction;
use yii\base\Event;
use yii\web\Response;

class ZoneController extends CrudController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => EasyAccessControl::class,
                'actions' => [
                    'get-zones' => 'domain.read',

                    'create' => 'zone.create',
                    'update' => 'zone.update',
                    'delete' => 'zone.delete',

                    '*' => 'zone.read',
                ],
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
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
            'disable' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:domain', 'Zone has been disabled'),
                'on beforeSave' => function (Event $event): void {
                    /** @var \hipanel\actions\Action $action */
                    $action = $event->sender;
                    $registry = Yii::$app->request->post('registry');
                    foreach ($action->collection->models as $model) {
                        $model->state = Zone::STATE_NOT_WORKING;
                        $model->registry = $registry[$model->id];
                    }
                },
            ],
            'enable' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:domain', 'Zone has been enabled'),
                'on beforeSave' => function (Event $event): void {
                    /** @var \hipanel\actions\Action $action */
                    $action = $event->sender;
                    $registry = Yii::$app->request->post('registry');
                    foreach ($action->collection->models as $model) {
                        $model->state = Zone::STATE_OK;
                        $model->registry = $registry[$model->id];
                    }
                },
            ],
            'validate-form' => [
                'class' => ValidateFormAction::class,
            ],
        ]);
    }

    public function actionGetZones(): array
    {
        $response = Yii::$app->response;
        $request = Yii::$app->request;
        $user = Yii::$app->user->identity;
        $response->format = Response::FORMAT_JSON;
        $search = $request->post('search');
        $models = [];
        $apiData = Yii::$app->cache->getOrSet(['get-zones-data', $user->id], function () {
            return Yii::$app->hiart->createCommand()->perform('get-zones', '')->getData();
        }, 3600 * 60);
        foreach ($apiData as $id => $zone) {
            if (empty($search)) {
                $models[] = ['id' => $id, 'text' => $zone];
            } elseif (mb_stripos($zone, $search) !== false) {
                $models[] = ['id' => $id, 'text' => $zone];
            }
        }

        return $models;
    }
}
