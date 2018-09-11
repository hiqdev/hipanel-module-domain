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
                    '*' => 'domain.read',
                ],
            ],
        ]);
    }

    public function actionGetZones()
    {
        $response = Yii::$app->response;
        $request = Yii::$app->request;
        $response->format = Response::FORMAT_JSON;
        $searchTerm = $request->post('name');
        $models = [];
        $apiData = Yii::$app->hiart->createCommand()->perform('get-zones', '')->getData();
        foreach ($apiData as $id => $zone) {
            if ($searchTerm) {
                if (stripos($zone, $searchTerm) !== false) {
                    $models[] = ['id' => $id, 'text' => $zone];
                }
            } else {
                $models[] = ['id' => $id, 'text' => $zone];
            }
        }

        return $models;
    }
}
