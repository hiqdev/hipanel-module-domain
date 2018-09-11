<?php

namespace hipanel\modules\domain\controllers;

use hipanel\filters\EasyAccessControl;
use Yii;
use yii\web\Response;

class ZoneController extends \hipanel\base\CrudController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => EasyAccessControl::class,
                'actions' => [
                    'get-zones' => 'domain.read',
                ],
            ],
        ]);
    }

    public function actionGetZones()
    {
        $response = Yii::$app->response;
        $request = Yii::$app->request;
        $response->format = Response::FORMAT_JSON;
        $search = $request->post('search');
        $models = [];
        $apiData = Yii::$app->cache->getOrSet(['get-zones-data'], function () {
            return Yii::$app->hiart->createCommand()->perform('get-zones', '')->getData();
        }, 3600 * 60);
        foreach ($apiData as $id => $zone) {
            if ($search) {
                if (mb_stripos($zone, $search) !== false) {
                    $models[] = ['id' => $id, 'text' => $zone];
                }
            } else {
                $models[] = ['id' => $id, 'text' => $zone];
            }
        }

        return $models;
    }
}
