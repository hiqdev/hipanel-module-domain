<?php

namespace hipanel\modules\domainchecker\controllers;

use hipanel\modules\domain\models\Domain;
use Yii;

class WhoisController extends \hipanel\base\CrudController
{
    public function actionIndex()
    {
        return $this->render('index', [
            'domain' => Yii::$app->request->get('domain'),
        ]);
    }

    public function actionLookup($domain)
    {
        $model = new Domain;
        $model->scenario = 'get-whois';

        if ($model->load(Yii::$app->request->get(), '')) {
            $a = 1;
        }

        return $this->renderPartial('_view', [

        ]);
    }
}
