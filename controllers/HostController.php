<?php
/**
 * @link    http://hiqdev.com/hipanel-module-domain
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\domain\controllers;

use hipanel\modules\domain\models\Host;
use Yii;

class HostController extends \hipanel\base\CrudController
{
    public function actionCreate()
    {
        $model = new Host();
        $model->scenario = 'insert';
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate()
    {
        return $this->render('update', []);
    }
}
