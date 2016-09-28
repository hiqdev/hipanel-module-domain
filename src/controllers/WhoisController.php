<?php

namespace hipanel\modules\domainchecker\controllers;

use hipanel\modules\domain\models\Domain;
use yii\web\UnprocessableEntityHttpException;
use Yii;

class WhoisController extends \hipanel\base\CrudController
{
    private function getModel()
    {
        $model = new Domain;
        $model->scenario = 'get-whois';

        return $model;
    }

    public function actionIndex($domain = null)
    {
        $model = $this->getModel();
        $model->load(Yii::$app->request->get(), '');
        if (!$model->validate()) {
            throw new UnprocessableEntityHttpException();
        }
        $availableZones = [];

        return $this->render('index', [
            'model' => $model,
            'availableZones' => $availableZones,
        ]);
    }

    public function actionLookup()
    {
        $request = Yii::$app->request;
        $model = $this->getModel();
        $model->load(Yii::$app->request->post(), '');
        if ($request->isAjax && $model->validate()) {
            sleep(1);
            $sShotSrc = sprintf('//mini.s-shot.ru/1920x1200/JPEG/1920/Z100/?%s', $model->domain);

            return $this->renderPartial('_view', [
                'model' => $model,
                'sShotSrc' => $sShotSrc,
            ]);
        }

        Yii::$app->end();
    }
}
