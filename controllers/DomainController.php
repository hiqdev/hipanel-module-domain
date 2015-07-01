<?php
/**
 * @link    http://hiqdev.com/hipanel-module-domain
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\domain\controllers;

use hipanel\modules\domain\models\Domain;
use Yii;
use yii\base\DynamicModel;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;

class DomainController extends \hipanel\base\CrudController
{
    public function actions()
    {
        return [
            'setnote' => [
                'class' => 'hiqdev\xeditable\XEditableAction',
                'scenario' => 'set-note',
                'modelclass' => Domain::className(),
            ],
            'set-autorenewal' => [
                'class'   => 'hipanel\actions\SwitchAction',
                'success' => Yii::t('app', 'Record was changed'),
                'error'   => Yii::t('app', 'Error occurred!'),
                'POST pjax' => [
                    'save' => true,
                    'success' => [
                        'class'  => 'hipanel\actions\ProxyAction',
                        'action' => 'index'
                    ]
                ],
                'POST'    => [
                    'save'    => true,
                    'success' => [
                        'class'  => 'hipanel\actions\RenderJsonAction',
                        'return' => function ($action) {
                            /** @var \hipanel\actions\Action $action */
                            return $action->collection->models;
                        }
                    ]
                ],
            ],
            'set-whois-protect' => [
                'class'   => 'hipanel\actions\SwitchAction',
                'success' => Yii::t('app', 'Record was changed'),
                'error'   => Yii::t('app', 'Error occurred!'),
                'POST pjax' => [
                    'save' => true,
                    'success' => [
                        'class'  => 'hipanel\actions\ProxyAction',
                        'action' => 'index'
                    ]
                ],
                'POST'    => [
                    'save'    => true,
                    'success' => [
                        'class'  => 'hipanel\actions\RenderJsonAction',
                        'return' => function ($action) {
                            /** @var \hipanel\actions\Action $action */
                            return $action->collection->models;
                        }
                    ]
                ],
            ],
            'set-lock' => [
                'class' => 'hipanel\actions\SwitchAction',
                'success' => Yii::t('app', 'Record was changed'),
                'error'   => Yii::t('app', 'Error occurred!'),
                'POST pjax' => [
                    'save' => true,
                    'success' => [
                        'class'  => 'hipanel\actions\ProxyAction',
                        'action' => 'index'
                    ]
                ],
                'POST'    => [
                    'save'    => true,
                    'success' => [
                        'class'  => 'hipanel\actions\RenderJsonAction',
                        'return' => function ($action) {
                            /** @var \hipanel\actions\Action $action */
                            return $action->collection->models;
                        }
                    ]
                ],
            ],
            'change-password' => [
                'class' => 'hipanel\actions\SwitchAction',
                'success' => Yii::t('app', 'Record was changed'),
                'error'   => Yii::t('app', 'Error occurred!'),
                'POST'    => [
                    'save'    => true,
                    'success' => [
                        'class'  => 'hipanel\actions\RefreshAction',
                        'return' => function ($action) {
                            /** @var \hipanel\actions\Action $action */
                            return $action->collection->models;
                        }
                    ]
                ],
            ],
        ];
    }

    public function actionView($id)
    {
//        $model = Domain::findOne(['id' => $id, 'with_dns' => 1]);
//        $model = Domain::findOne($id);
        $rawData = Domain::perform('GetInfo', ['id' => $id, 'with_dns' => 1]);
        $model = new Domain();
        $model->load($rawData, '');
        $domainContactInfo = Domain::perform('GetContactsInfo', ['id' => $id]);

        $pincodeModel = new DynamicModel(['pincode']);

        return $this->render('view', [
            'model' => $model,
            'domainContactInfo' => $domainContactInfo,
            'pincodeModel' => $pincodeModel,
        ]);
    }

    public function actionSync($id)
    {

    }

    public function actionPush()
    {

    }

    public function actionGetPassword()
    {
        sleep(1);
        $return = ['status' => 'error'];
        $id = Yii::$app->request->post('id');
        $pincode = Yii::$app->request->post('pincode');

        $model = DynamicModel::validateData(compact('id', 'pincode'), [
            [['id', 'pincode'], 'required'],
            [['id'], 'integer'],
            [['pincode'], 'trim'],
        ]);

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (!$model->hasErrors()) {
            try {
                $return = Domain::perform('GetPassword', ['id' => $id, 'pincode' => $pincode]);
            } catch (\Exception $e) {
                $return = array_merge($return, ['info' => $e->getMessage()]);
            }
        } else
            $return = array_merge($return, ['info' => Yii::t('app', 'Invalid data input')]);

        return $return;
    }

//    public function actionChangePassword($id)
//    {
////        Domain::perform('RegenPassword', ['id' => $id]);
//        return $this->refresh();
//    }
}