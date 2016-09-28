<?php

namespace hipanel\modules\domainchecker\controllers;

use hipanel\modules\domain\models\Domain;
use Yii;

class TransferController extends \hipanel\base\CrudController
{

    public function actionView($domain)
    {
        $model = new Domain;
        $model->scenario = 'get-whois';

        if ($model->load(Yii::$app->request->get(), '')) {
            $a = 1;
        }

        return $this->render('view', []);
    }
}
