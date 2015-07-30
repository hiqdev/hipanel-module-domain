<?php
/**
 * @link    http://hiqdev.com/hipanel-module-domain
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\domain\controllers;

use hiqdev\hiart\Collection;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\web\HttpException;
use yii\widgets\ActiveForm;

class HostController extends \hipanel\base\CrudController
{

    public function actions()
    {
        return [
            'create' => [
                'class'     => 'hipanel\actions\SmartCreateAction',
                'success'   => Yii::t('app', 'Name server created'),
            ],
            'update' => [
                'class'     => 'hipanel\actions\SmartUpdateAction',
                'success'   => Yii::t('app', 'Name server updated'),
            ],
            'delete' => [
                'class'     => 'hipanel\actions\SmartDeleteAction',
                'success'   => Yii::t('app', 'Name server deleted'),
            ],
        ];
    }

    public function actionCreateOld()
    {
        $model = static::newModel(['scenario' => 'create']);
        if (Yii::$app->request->isPost) {
            $collection = Yii::createObject([
                'class' => Collection::className(),
                'model' => $model,
            ]);
            if ($collection->load() && $collection->save())
                return $this->redirect(['index']);
        }

        return $this->render('create', ['models' => [$model]]);
    }

    public function actionUpdateOld($id = null)
    {

        $condition = Yii::$app->request->get('selection') ?: $id;
        $models = $this->findModels($condition);
        if (Yii::$app->request->isPost) {
            $collection = Yii::createObject([
                'class' => Collection::className(),
                'model' => static::newModel(['scenario' => 'update']),
            ]);
            $collection->load();
            if (!$collection->validate() && Yii::$app->request->isAjax) {
                throw new HttpException(406, $collection->firstError);
            }
            if ($collection->save()) {
                if (!Yii::$app->request->isAjax) {
                    return $this->redirect(['index']);
                }
            } else {
                \yii\helpers\VarDumper::dump($collection, 10, true);die();
            }
        }

        return $this->render('update', ['models' => $models]);

    }

    public function actionValidateForm($scenario)
    {
        $model = static::newModel(['scenario' => $scenario]);

        if (Yii::$app->request->isPost) {
            $collection = Yii::createObject([
                'class' => Collection::className(),
                'model' => $model,
            ]);
            $collection->load();
            return $this->renderJson(ActiveForm::validateMultiple($collection->models));
        }
    }

    public function actionDeleteOld()
    {
        $condition = Yii::$app->request->post('selection');
        if (!empty($condition)) {
            $models = $this->findModels($condition);
            foreach ($models as $model) {
                $model->delete();
            }
        }

        return $this->redirect('index');
    }
}
