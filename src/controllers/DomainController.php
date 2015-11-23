<?php

/*
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2015, HiQDev (http://hiqdev.com/)
 */

/**
 * @link    http://hiqdev.com/hipanel-module-domain
 *
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */
namespace hipanel\modules\domain\controllers;

use hipanel\helpers\ArrayHelper;
use hipanel\models\Ref;
use hipanel\modules\client\models\Contact;
use hipanel\modules\domain\models\Domain;
use hipanel\modules\finance\models\Resource;
use hipanel\modules\finance\models\Tariff;
use hiqdev\hiart\Collection;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Yii;
use yii\base\DynamicModel;
use yii\data\ArrayDataProvider;

class DomainController extends \hipanel\base\CrudController
{
    public function actions()
    {
        return [
            'add-to-cart-renewal' => [
                'class'        => 'hiqdev\yii2\cart\actions\AddToCartAction',
                'productClass' => 'hipanel\modules\domain\cart\DomainRenewalProduct',
            ],
            'add-to-cart-registration' => [
                'class'        => 'hiqdev\yii2\cart\actions\AddToCartAction',
                'productClass' => 'hipanel\modules\domain\cart\DomainRegistrationProduct',
            ],
            'add-to-cart-transfer' => [
                'class'        => 'hiqdev\yii2\cart\actions\AddToCartAction',
                'productClass' => 'hipanel\modules\domain\cart\DomainTransferProduct',
                'bulkLoad'     => true,
            ],
            'index' => [
                'class' => 'hipanel\actions\IndexAction',
                'data' => function ($action) {
                    return [
                        'stateData' => $action->controller->getStateData(),
                    ];
                },
            ],
            'view' => [
                'class' => 'hipanel\actions\ViewAction',
                'findOptions' => ['with_dns' => 1],
                'data' => function ($action) {
                    return [
                        'domainContactInfo' => Domain::perform('GetContactsInfo', ['id' => $action->getId()]),
                        'pincodeModel' => new DynamicModel(['pincode']),
                    ];
                },
            ],
            'validate-form' => [
                'class' => 'hipanel\actions\ValidateFormAction',
            ],
            'validate-nss' => [
                'class'     => 'hipanel\actions\ValidateFormAction',
            ],
            'set-note' => [
                'class' => 'hipanel\actions\SmartUpdateAction',
                'success' => Yii::t('app', 'Note changed'),
                'error' => Yii::t('app', 'Failed change note'),
            ],
            'set-nss' => [
                'class' => 'hipanel\actions\SmartUpdateAction',
                'success' => Yii::t('app', 'Nameservers changed'),
            ],
            'delete' => [
                'class'     => 'hipanel\actions\SmartDeleteAction',
                'scenario'  => 'only-object',
                'success'   => Yii::t('app', 'Domain deleted'),
                'error'     => Yii::t('app', 'Failed delete domain'),
            ],
            'delete-agp' => [
                'class'     => 'hipanel\actions\SmartPerformAction',
                'scenario'  => 'only-object',
                'success'   => Yii::t('app', 'Domain deleted'),
                'error'     => Yii::t('app', 'Failed delete domain'),
            ],
            'cancel-transfer' => [
                'class'     => 'hipanel\actions\SmartPerformAction',
                'scenario'  => 'only-object',
                'success'   => Yii::t('app', 'Domain transfer was canceled'),
                'error'     => Yii::t('app', 'Failed cancel transfer domain'),
            ],
            'reject-transfer' => [
                'class'     => 'hipanel\actions\SmartPerformAction',
                'scenario'  => 'only-object',
                'success'   => Yii::t('app', 'Domain transfer was rejected'),
                'error'     => Yii::t('app', 'Failed reject transfer domain'),
            ],
            'approve-transfer' => [
                'class'     => 'hipanel\actions\SmartPerformAction',
                'scenario'  => 'only-object',
                'success'   => Yii::t('app', 'Domain transfer was approved'),
                'error'     => Yii::t('app', 'Failed approve transfer domain'),
            ],
            'notify-transfer-in' => [
                'class'     => 'hipanel\actions\SmartPerformAction',
                'scenario'  => 'only-object',
                'success'   => Yii::t('app', 'FOA sended'),
                'error'     => Yii::t('app', 'Failed FOA send'),
            ],
            'enable-hold' => [
                'class'     => 'hipanel\actions\SmartPerformAction',
                'scenario'  => 'only-object',
                'success'   => Yii::t('app', 'Hold was enabled'),
                'error'     => Yii::t('app', 'Failed enabling Hold'),
            ],
            'disable-hold' => [
                'class'     => 'hipanel\actions\SmartPerformAction',
                'scenario'  => 'only-object',
                'success'   => Yii::t('app', 'Hold was disabled'),
                'error'     => Yii::t('app', 'Failed disabling Hold'),
            ],
            'enable-freeze' => [
                'class'     => 'hipanel\actions\SmartPerformAction',
                'scenario'  => 'only-object',
                'success'   => Yii::t('app', 'Freeze was enabled'),
            ],
            'disable-freeze' => [
                'class'     => 'hipanel\actions\SmartPerformAction',
                'scenario'  => 'only-object',
                'success'   => Yii::t('app', 'Freeze was disabled'),
            ],
            'OLD-set-ns' => [
                'class'     => 'hipanel\actions\SwitchAction',
                'beforeSave' => function ($action) {
                    $templateModel = null;
                    $template = Yii::$app->request->post('check');
                    foreach ($action->collection->models as $model) {
                        if ($model->id === $template) {
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
                        'class' => 'hipanel\actions\RenderJsonAction',
                        'return' => function ($action) {
                            /* @var \hipanel\actions\Action $action */
                            return $action->collection->models;
                        },
                    ],
                    'error' => [
                        'class' => 'hipanel\actions\RenderJsonAction',
                        'return' => function ($action) {
                            /* @var \hipanel\actions\Action $action */
                            return $action->collection->getFirstError();
                        },
                    ],
                ],
            ],
            // Premium Autorenewal
            'set-paid-feature-autorenewal' => [
                'class'     => 'hipanel\actions\SmartPerformAction',
                'scenario'  => 'set-autorenewal',
                'success'   => Yii::t('app', 'Premium autorenewal has been changed'),
                'beforeSave' => function ($action) {
                    foreach ($action->collection->models as $model) {
                        $model->autorenewal = 1;
                    }
                },
            ],
            // Autorenewal
            'set-autorenewal' => [
                'class'     => 'hipanel\actions\SmartPerformAction',
                'success'   => Yii::t('app', 'Autorenewal has been change'),
            ],
            'enable-autorenewal' => [
                'class'     => 'hipanel\actions\SmartPerformAction',
                'success'   => Yii::t('app', 'Autorenewal has been enabled'),
                'scenario'  => 'set-autorenewal',
                'beforeSave' => function ($action) {
                    foreach ($action->collection->models as $model) {
                        $model->autorenewal = 1;
                    }
                },
            ],
            'disable-autorenewal' => [
                'class'     => 'hipanel\actions\SmartPerformAction',
                'success'   => Yii::t('app', 'Autorenewal has been disabled'),
                'scenario'  => 'set-autorenewal',
                'beforeSave' => function ($action) {
                    foreach ($action->collection->models as $model) {
                        $model->autorenewal = 0;
                    }
                },
            ],
            // Whois protect
            'set-whois-protect' => [
                'class'     => 'hipanel\actions\SmartPerformAction',
                'success'   => Yii::t('app', 'whois protect is changed'),
            ],
            'enable-whois-protect' => [
                'class'     => 'hipanel\actions\SmartPerformAction',
                'success'   => Yii::t('app', 'whois protect is enabled'),
                'scenario'  => 'set-whois-protect',
                'beforeSave' => function ($action) {
                    foreach ($action->collection->models as $model) {
                        $model->enable = 1;
                    }
                },
            ],
            'disable-whois-protect' => [
                'class'     => 'hipanel\actions\SmartPerformAction',
                'success'   => Yii::t('app', 'whois protect is disabled'),
                'scenario'  => 'set-whois-protect',
                'beforeSave' => function ($action) {
                    foreach ($action->collection->models as $model) {
                        $model->enable = 0;
                    }
                },
            ],
            // Lock
            'set-lock' => [
                'class'     => 'hipanel\actions\SmartPerformAction',
                'success'   => Yii::t('app', 'Records was changed'),
            ],
            'enable-lock' => [
                'class'     => 'hipanel\actions\SmartPerformAction',
                'success'   => Yii::t('app', 'Lock was enabled'),
                'scenario'  => 'set-lock',
                'beforeSave' => function ($action) {
                    foreach ($action->collection->models as $model) {
                        $model->enable = 1;
                    }
                },
            ],
            'disable-lock' => [
                'class'     => 'hipanel\actions\SmartPerformAction',
                'success'   => Yii::t('app', 'Lock was disabled'),
                'scenario'  => 'set-lock',
                'beforeSave' => function ($action) {
                    foreach ($action->collection->models as $model) {
                        $model->enable = 0;
                    }
                },
            ],
            'sync' => [
                'class'     => 'hipanel\actions\SmartPerformAction',
                'success'   => Yii::t('app', 'Domain contacts synced'),
            ],

            'buy' => [
                'class'     => 'hipanel\actions\RedirectAction',
                'url'       => Yii::$app->params['orgUrl'],
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

    public function actionCheck()
    {
        $domain = Yii::$app->request->post('domain');
        $result = Domain::perform('Check', ['domains' => $domain], true);

        return '<td>' . $domain . '</td><td>com</td><td>123</td><td>asdf</td>';
    }

    public function actionTestSleep()
    {
        sleep(5);

        return 'wake';
    }

    public function actionTestPerform()
    {
        $result = Domain::perform('Check', ['domains' => 'test' . microtime() . '.com'], true);

        return 'wake';
    }

    public function actionTestFastAnswer()
    {
        return 'fast ok';
    }

    public function actionTransfer()
    {
        $model = new Domain();
        $model->scenario = 'transfer';
        $transferDataProvider = null;
        if (Yii::$app->request->isPost) {
            $model = (new Collection(['model' => $model]))->load()->first;
            Yii::$app->session->setFlash('transferGrid', 1);
            $transferDataProvider = new ArrayDataProvider();
            $transferDataProvider->setModels($model->getTransferDataProviderOptions());
        }

        return $this->render('transfer', [
            'model' => $model,
            'transferDataProvider' => $transferDataProvider,
        ]);
    }

    /**
     * @return string
     */
    public function actionCheckDomain()
    {
        $model = new Domain();
        $model->scenario = 'check-domain';
        $zones_z = Domain::perform('GetZones');
        $tariffs = Tariff::find(['scenario' => 'get-available-info'])
            ->joinWith('resources')
            ->andFilterWhere(['type' => 'domain'])
            ->andFilterWhere(['seller' => 'ahnames'])
            ->one();
        $zones = array_filter($tariffs->resources ?: [], function ($resource) {
            return ($resource->zone !== null && $resource->type === Resource::TYPE_DOMAIN_REGISTRATION);
        });
        $dropDownZones = [];
        foreach ($zones_z as $zone) {
            $dropDownZones[$zone] = '.' . $zone;
        }
        $domainCheckDataProvider = new ArrayDataProvider();
        if ($model->load(Yii::$app->request->get())) {
            $domains = [$model->domain . '.' . $model->zone];
            foreach ($dropDownZones as $zone => $label) {
                $domains[] = $model->domain . '.' . $zone;
            }
//            $data = Yii::$app->get('cache')->getTimeCached(120, [$domains], function ($domains) use ($model) {
//                return $model->perform('Check', ['domains' => implode(',', array_unique($domains))], true);
//            });
            $results = [];
            foreach ($domains as $domain) {
                foreach ($zones as $resource) {
                    if ($resource->zone === substr($domain, strpos($domain, '.') + 1)) {
                        $tariff = $resource;
                        break;
                    }
                }
                $results[] = new Domain([
                    'domain' => $domain,
                    'is_available' => (bool) 1,
                    'zone' => substr($domain, strpos($domain, '.') + 1),
                    'resource' => $tariff,
                ]);
            }
            $domainCheckDataProvider->setModels($results);
        }

        return $this->render('checkDomain', [
            'model' => $model,
            'dropDownZonesOptions' => $dropDownZones,
            'domainCheckDataProvider' => $domainCheckDataProvider,
        ]);
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
        } else {
            $return = array_merge($return, ['info' => $model->getFirstError('pincode')]);
        }

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
        } else {
            $return = array_merge($return, ['info' => Yii::t('app', 'Invalid data input')]);
        }

        return $return;
    }

    /**
     * @throws \HttpInvalidParamException
     *
     * @return string
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
        } else {
            return Yii::t('app', 'No domains is check');
        }
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
                'models' => $collection->models,
            ]);
        } else {
            return Yii::t('app', 'No domains is check');
        }
    }

    public function actionSetContacts()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $model = DynamicModel::validateData($post, [
            [Domain::$contactOptions, 'required'],
        ]);

        if ($model->hasErrors()) {
            return ['errors' => $model->errors];
        }

        $ids = Yii::$app->request->post('id');
        $data = iterator_to_array(
            new RecursiveIteratorIterator(
                new RecursiveArrayIterator(
                    array_map(
                        function ($i) use ($post) {
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
                ],
            ];
        }

        return $result;
    }

    public function actionGetContactsByAjax($id)
    {
        if (Yii::$app->request->isAjax) {
            $domainContactInfo = Domain::perform('GetContactsInfo', ['id' => $id]);

            return $this->renderAjax('_contactsTables', ['domainContactInfo' => $domainContactInfo]);
        } else {
            Yii::$app->end();
        }
    }

    public function getStateData()
    {
        return Ref::getList('state,domain');
    }
}
