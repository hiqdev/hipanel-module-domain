<?php
/**
 * @link    http://hiqdev.com/hipanel-module-domain
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\domain\controllers;

use hipanel\helpers\ArrayHelper;
use hipanel\modules\client\models\Contact;
use hipanel\modules\domain\models\Domain;
use hiqdev\hiart\Collection;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Yii;
use yii\base\DynamicModel;
use yii\base\InvalidParamException;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class DomainController extends \hipanel\base\CrudController
{
    public function actions()
    {
        return [
            'index' => [
                'class'     => 'hipanel\actions\IndexAction',
            ],
            'validate-form' => [
                'class' => 'hipanel\actions\ValidateFormAction',
            ],
            'set-note' => [
                'class'     => 'hipanel\actions\SmartUpdateAction',
                'success'   => Yii::t('app', 'Note changed'),
                'error'     => Yii::t('app', 'Failed change note'),
            ],
            'set-ns' => [
                'class' => 'hipanel\actions\SwitchAction',
                'beforeSave' => function ($action) {
                    $templateModel = null;
                    $template = Yii::$app->request->post('check');
                    foreach ($action->collection->models as $model) {
                        if ($model->id == $template) {
                            $templateModel = $model;
                        }
                    }

                    foreach ($action->collection->models as $model) {
                        $model->nameservers = $templateModel->nameservers;
                    }
                },
                'POST' => [
                    'save' => true,
                    'success' => [
                        'class'  => 'hipanel\actions\RenderJsonAction',
                        'return' => function ($action) {
                            /** @var \hipanel\actions\Action $action */
                            return $action->collection->models;
                        }
                    ],
                    'error' => [
                        'class'  => 'hipanel\actions\RenderJsonAction',
                        'return' => function ($action) {
                            /** @var \hipanel\actions\Action $action */
                            return $action->collection->getFirstError();
                        }
                    ]
                ],
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
//            'change-password' => [
//                'class' => 'hipanel\actions\SwitchAction',
//                'success' => Yii::t('app', 'Record was changed'),
//                'error'   => Yii::t('app', 'Error occurred!'),
//                'POST'    => [
//                    'save'    => true,
//                    'success' => [
//                        'class'  => 'hipanel\actions\RefreshAction',
//                        'return' => function ($action) {
//                            /** @var \hipanel\actions\Action $action */
//                            return $action->collection->models;
//                        }
//                    ]
//                ],
//            ],
        ];
    }

    public function actionView($id)
    {
        $model = Domain::findOne(['id' => $id, 'with_dns' => 1]);
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
            $return = array_merge($return, ['info' => $model->getFirstError('pincode')]);

        return $return;
    }

    public function actionChangePassword()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $return = ['status' => 'error'];
        $id = Yii::$app->request->post('id');
        $model = DynamicModel::validateData(compact('id'), [
            [['id'], 'required'],
            [['id'], 'integer'],
        ]);
        if (!$model->hasErrors()) {
            try {
                $return = Domain::perform('RegenPassword', ['id' => $id]);
            } catch (\Exception $e) {
                $return = array_merge($return, ['info' => $e->getMessage()]);
            }
        } else
            $return = array_merge($return, ['info' => Yii::t('app', 'Invalid data input')]);

        return $return;
    }

    /**
     * @return string
     * @throws \HttpInvalidParamException
     * @throws \hiqdev\hiart\HiResException
     */
    public function actionModalContactsBody()
    {
        $ids = ArrayHelper::csplit(Yii::$app->request->post('ids'));
        if ($ids) {
            $domainContacts = Domain::perform('GetContacts', ArrayHelper::make_sub($ids, 'id'), true);
            $modelContactInfo = Contact::perform('GetList', ['domain_ids' => $ids, 'limit' => 1000], true);
            return $this->renderAjax('_modalContactsBody', [
                'domainContacts' => $domainContacts,
                'modelContactInfo' => $modelContactInfo,
            ]);
        } else return Yii::t('app', 'No domains is check');
    }

    public function actionModalNsBody()
    {
        $ids = ArrayHelper::csplit(Yii::$app->request->post('ids'));
        if ($ids) {
//            $models = static::newModel()->find()->where([
//                'id' => $ids,
//            ])->search(null, ['scenario' => 'GetNSs']);
//
//            /*
//             * Domain[12323][id] = 12323
//             * Domain[12322][id] = 12322
//             * Domain[12324][id] = 12324
//             * Domain[12325][id] = 12325
//             */
            $model = $this->newModel(['scenario' => 'set-ns']);
            $collection = (new Collection(['model' => $model]))->load($model::perform('GetNSs', ArrayHelper::make_sub($ids, 'id'), true));
//            $collection = (new Collection(['model' => $this->newModel()]))->load()->perform('GetNSs');
            return $this->renderAjax('_modalNsBody', [
                'models' => $collection->models
            ]);
        } else return Yii::t('app', 'No domains is check');
    }

    public function actionSetContacts()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $model = DynamicModel::validateData($post, [
            [Domain::$contactOptions, 'required'],
        ]);

        if ($model->hasErrors())
            return ['errors' => $model->errors];

        $ids = Yii::$app->request->post('id');
        $data = iterator_to_array(
            new RecursiveIteratorIterator(
                new RecursiveArrayIterator(
                    array_map(
                        function($i) use ($post) {
                            return [$i => $post[$i]];
                        },
                        Domain::$contactOptions)
                )
            )
        );
        $preparedData = [];
        foreach ($ids as $id) {
            $preparedData[] = ArrayHelper::merge(['id' => $id], $data);
        }
        try {
            $result = Domain::perform('SetContacts', $preparedData, true);
        } catch (\Exception $e) {
            $result = [
                'errors' => [
                    'title' => $e->getMessage(),
                    'detail' => $e->getMessage(),
                ]
            ];
        }
        return $result;
    }

    public function actionGetContactsByAjax($id)
    {
        if (Yii::$app->request->isAjax) {
            $domainContactInfo = Domain::perform('GetContactsInfo', ['id' => $id]);
            return $this->renderAjax('_contactsTables', ['domainContactInfo' => $domainContactInfo]);
        } else
            Yii::$app->end();
    }


}
