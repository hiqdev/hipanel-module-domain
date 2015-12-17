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

use hipanel\actions\Action;
use hipanel\actions\IndexAction;
use hipanel\actions\ProxyAction;
use hipanel\actions\RedirectAction;
use hipanel\actions\RenderAction;
use hipanel\actions\RenderJsonAction;
use hipanel\actions\SmartDeleteAction;
use hipanel\actions\SmartPerformAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\actions\ValidateFormAction;
use hipanel\actions\ViewAction;
use hipanel\helpers\ArrayHelper;
use hipanel\models\Ref;
use hipanel\modules\client\models\Client;
use hipanel\modules\client\models\Contact;
use hipanel\modules\domain\cart\DomainRegistrationProduct;
use hipanel\modules\domain\cart\DomainRenewalProduct;
use hipanel\modules\domain\cart\DomainTransferProduct;
use hipanel\modules\domain\models\Domain;
use hipanel\modules\domain\models\DomainSearch;
use hipanel\modules\finance\models\Resource;
use hipanel\modules\finance\models\Tariff;
use hiqdev\hiart\Collection;
use hiqdev\yii2\cart\actions\AddToCartAction;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Yii;
use yii\base\DynamicModel;
use yii\base\Event;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;

class DomainController extends \hipanel\base\CrudController
{
    public function actions()
    {
        return [
            'add-to-cart-renewal' => [
                'class'        => AddToCartAction::class,
                'productClass' => DomainRenewalProduct::class,
            ],
            'add-to-cart-registration' => [
                'class'        => AddToCartAction::class,
                'productClass' => DomainRegistrationProduct::class,
            ],
            'add-to-cart-transfer' => [
                'class'        => AddToCartAction::class,
                'productClass' => DomainTransferProduct::class,
                'bulkLoad'     => true,
            ],
            'push' => [
                'class' => SmartPerformAction::class,
                'POST'      => [
                    'save'    => true,
                    'success' => [
                        'class' => RedirectAction::class,
                        'url' => 'index',
                    ],
                    'error' => [
                        'class' => RedirectAction::class,
                    ],
                ],
            ],
            'index' => [
                'class' => IndexAction::class,
                'data' => function ($action) {
                    return [
                        'stateData' => $action->controller->getStateData(),
                    ];
                },
            ],
            'view' => [
                'class' => ViewAction::class,
                'findOptions' => ['with_nsips' => 1],
                'data' => function ($action) {
                    return [
                        'domainContactInfo' => Domain::perform('GetContactsInfo', ['id' => $action->getId()]),
                        'pincodeModel' => new DynamicModel(['pincode']),
                    ];
                },
            ],
            'validate-form' => [
                'class' => ValidateFormAction::class,
            ],
            'validate-nss' => [
                'class'     => ValidateFormAction::class,
                'model'     => 'hipanel\modules\domain\models\Ns',
                'scenario'  => 'default',

                'allowDynamicScenario' => false,
            ],
            'set-note' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('app', 'Note changed'),
                'error' => Yii::t('app', 'Failed change note'),
                'POST html' => [
                    'save'    => true,
                    'success' => [
                        'class' => RedirectAction::class,
                        'url'   => function ($action) {
                            return $action->redirect($this->redirect(Yii::$app->request->referrer));
                        }
                    ],
                ],
                'on beforeSave' => function (Event $event) {
                    /** @var \hipanel\actions\Action $action */
                    $action = $event->sender;
                    $bulkNote = Yii::$app->request->post('bulk_note');
                    if (!empty($bulkNote)) {
                        foreach ($action->collection->models as $model) {
                            $model->note = $bulkNote;
                        }
                    }
                },
            ],
            'set-nss' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('app', 'Nameservers changed'),
                'POST html' => [
                    'save'    => true,
                    'success' => [
                        'class' => RedirectAction::class,
                        'url'   => function ($action) {
                            return $action->controller->redirect(Yii::$app->request->referrer);
                        }
                    ],
                ],
            ],
            'bulk-set-nss' => [
                'class' => SmartUpdateAction::class,
                'scenario' => 'set-nss',
                'view' => '_bulkSetNs',
                'success' => Yii::t('app', 'Nameservers changed'),
                'POST pjax' => [
                    'save'    => true,
                    'success' => [
                        'class' => ProxyAction::class,
                        'return'   => function ($action) {
                            return ['fuck' => 'yeah'];
                        }
                    ],
                ],
                'on beforeFetch' => function (Event $event) {
                    /** @var \hipanel\actions\SearchAction $action */
                    $action = $event->sender;
                    $dataProvider = $action->getDataProvider();
                    $dataProvider->query->andWhere(['with_nsips' => 1]);
                },
                'on beforeSave' => function (Event $event) {
                    /** @var \hipanel\actions\Action $action */
                    $action = $event->sender;

                    $bulkNsIps = Yii::$app->request->post('nsips');
                    if ($bulkNsIps !== null) {
                        foreach ($action->collection->models as $model) {
                            $model->nsips = $bulkNsIps;
                        }
                    }
                },
            ],
            'delete' => [
                'class'     => SmartDeleteAction::class,
                'scenario'  => 'only-object',
                'success'   => Yii::t('app', 'Domain deleted'),
                'error'     => Yii::t('app', 'Failed delete domain'),
            ],
            'delete-agp' => [
                'class'     => SmartPerformAction::class,
                'scenario'  => 'only-object',
                'success'   => Yii::t('app', 'Domain deleted'),
                'error'     => Yii::t('app', 'Failed delete domain'),
            ],
            'cancel-transfer' => [
                'class'     => SmartPerformAction::class,
                'scenario'  => 'only-object',
                'success'   => Yii::t('app', 'Domain transfer was canceled'),
                'error'     => Yii::t('app', 'Failed cancel transfer domain'),
            ],
            'reject-transfer' => [
                'class'     => SmartPerformAction::class,
                'scenario'  => 'only-object',
                'success'   => Yii::t('app', 'Domain transfer was rejected'),
                'error'     => Yii::t('app', 'Failed reject transfer domain'),
            ],
            'approve-transfer' => [
                'class'     => SmartPerformAction::class,
                'scenario'  => 'only-object',
                'success'   => Yii::t('app', 'Domain transfer was approved'),
                'error'     => Yii::t('app', 'Failed approve transfer domain'),
            ],
            'notify-transfer-in' => [
                'class'     => SmartPerformAction::class,
                'scenario'  => 'only-object',
                'success'   => Yii::t('app', 'FOA sended'),
                'error'     => Yii::t('app', 'Failed FOA send'),
            ],
            'enable-hold' => [
                'class'     => SmartPerformAction::class,
                'scenario'  => 'only-object',
                'success'   => Yii::t('app', 'Hold was enabled'),
                'error'     => Yii::t('app', 'Failed enabling Hold'),
            ],
            'disable-hold' => [
                'class'     => SmartPerformAction::class,
                'scenario'  => 'only-object',
                'success'   => Yii::t('app', 'Hold was disabled'),
                'error'     => Yii::t('app', 'Failed disabling Hold'),
            ],
            'enable-freeze' => [
                'class'     => SmartPerformAction::class,
                'scenario'  => 'only-object',
                'success'   => Yii::t('app', 'Freeze was enabled'),
            ],
            'disable-freeze' => [
                'class'     => SmartPerformAction::class,
                'scenario'  => 'only-object',
                'success'   => Yii::t('app', 'Freeze was disabled'),
            ],
            'OLD-set-ns' => [
                'class'     => RenderAction::class,
                'on beforeSave' => function (Event $event) {
                    /** @var Action $action */
                    $action = $event->sender;
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
                        'class' => RenderJsonAction::class,
                        'return' => function ($action) {
                            /* @var Action $action */
                            return $action->collection->models;
                        },
                    ],
                    'error' => [
                        'class' => RenderJsonAction::class,
                        'return' => function ($action) {
                            /* @var Action $action */
                            return $action->collection->getFirstError();
                        },
                    ],
                ],
            ],
            // Premium Autorenewal
            'set-paid-feature-autorenewal' => [
                'class'     => SmartPerformAction::class,
                'scenario'  => 'set-autorenewal',
                'success'   => Yii::t('app', 'Premium autorenewal has been changed'),
                'on beforeSave' => function (Event $event) {
                    /** @var Action $action */
                    $action = $event->sender;
                    foreach ($action->collection->models as $model) {
                        $model->autorenewal = 1;
                    }
                },
            ],
            // Autorenewal
            'set-autorenewal' => [
                'class'     => SmartPerformAction::class,
                'success'   => Yii::t('app', 'Autorenewal has been change'),
            ],
            'enable-autorenewal' => [
                'class'     => SmartPerformAction::class,
                'success'   => Yii::t('app', 'Autorenewal has been enabled'),
                'scenario'  => 'set-autorenewal',
                'on beforeSave' => function (Event $event) {
                    /** @var Action $action */
                    $action = $event->sender;
                    foreach ($action->collection->models as $model) {
                        $model->autorenewal = 1;
                    }
                },
            ],
            'disable-autorenewal' => [
                'class'     => SmartPerformAction::class,
                'success'   => Yii::t('app', 'Autorenewal has been disabled'),
                'scenario'  => 'set-autorenewal',
                'on beforeSave' => function (Event $event) {
                    /** @var Action $action */
                    $action = $event->sender;
                    foreach ($action->collection->models as $model) {
                        $model->autorenewal = 0;
                    }
                },
            ],
            // Whois protect
            'set-whois-protect' => [
                'class'     => SmartPerformAction::class,
                'success'   => Yii::t('app', 'whois protect is changed'),
            ],
            'enable-whois-protect' => [
                'class'     => SmartPerformAction::class,
                'success'   => Yii::t('app', 'whois protect is enabled'),
                'scenario'  => 'set-whois-protect',
                'on beforeSave' => function (Event $event) {
                    /** @var Action $action */
                    $action = $event->sender;
                    foreach ($action->collection->models as $model) {
                        $model->enable = 1;
                    }
                },
            ],
            'disable-whois-protect' => [
                'class'     => SmartPerformAction::class,
                'success'   => Yii::t('app', 'whois protect is disabled'),
                'scenario'  => 'set-whois-protect',
                'on beforeSave' => function (Event $event) {
                    /** @var Action $action */
                    $action = $event->sender;
                    foreach ($action->collection->models as $model) {
                        $model->enable = 0;
                    }
                },
            ],
            // Lock
            'set-lock' => [
                'class'     => SmartPerformAction::class,
                'success'   => Yii::t('app', 'Records was changed'),
            ],
            'enable-lock' => [
                'class'     => SmartPerformAction::class,
                'success'   => Yii::t('app', 'Lock was enabled'),
                'scenario'  => 'set-lock',
                'on beforeSave' => function (Event $event) {
                    /** @var Action $action */
                    $action = $event->sender;
                    foreach ($action->collection->models as $model) {
                        $model->enable = 1;
                    }
                },
            ],
            'disable-lock' => [
                'class'     => SmartPerformAction::class,
                'success'   => Yii::t('app', 'Lock was disabled'),
                'scenario'  => 'set-lock',
                'on beforeSave' => function (Event $event) {
                    /** @var Action $action */
                    $action = $event->sender;
                    foreach ($action->collection->models as $model) {
                        $model->enable = 0;
                    }
                },
            ],
            'sync' => [
                'class'     => SmartPerformAction::class,
                'success'   => Yii::t('app', 'Domain contacts synced'),
            ],

            'buy' => [
                'class'     => RedirectAction::class,
                'url'       => Yii::$app->params['orgUrl'],
            ],

//            'change-password' => [
//                'class' => RenderAction::class,
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



    public function actionDomainPushModal($id)
    {
        $model = $this->findModel($id);
        $hasPincode = Client::perform('HasPincode', ['id' => Yii::$app->user->id]);
        $model->scenario = $hasPincode['pincode_enabled'] ? 'push-with-pincode' : 'push';



        return $this->renderAjax('_modalPush', [
            'model' => $model,
            'hasPincode' => $hasPincode,
        ]);
    }

    public function actionBulkSetNote()
    {
        $model = new Domain();
        $model->scenario = 'set-note';
        $collection = new Collection();
        $collection->setModel($model);
        $collection->load();
        $searchModel = new DomainSearch();
        $models = $searchModel
            ->search([$searchModel->formName() => ['id_in' => ArrayHelper::map($collection->models, 'id', 'id')]])
            ->getModels();

        return $this->renderAjax('_bulkSetNote', ['models' => $models]);
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

    public function actionCheck()
    {
        session_write_close();
        Yii::$app->hiresource->auth = function () {
            return [];
        };
        $domain = Yii::$app->request->post('domain');
        $domain = Html::encode($domain);
        list ($domainName, $zone) = explode('.', $domain, 2);
        $line['full_domain_name'] = $domain;
        $line['domain'] = $domainName;
        $line['zone'] = $zone;
        if ($domain) {
            $check = Domain::perform('Check', ['domains' => [$domain]], true);
            if ($check[$domain] === 0) {
                return $this->renderAjax('_checkDomainLine', [
                    'line' => $line,
                    'state' => 'unavailable',
                ]);
            } else {
                $tariffs = Tariff::find(['scenario' => 'get-available-info'])
                    ->joinWith('resources')
                    ->andFilterWhere(['type' => 'domain'])
                    ->andFilterWhere(['seller' => 'ahnames'])
                    ->one();
                $zones = array_filter($tariffs->resources ?: [], function ($resource) {
                    return ($resource->zone !== null && $resource->type === Resource::TYPE_DOMAIN_REGISTRATION);
                });
                foreach ($zones as $resource) {
                    if ($resource->zone === $line['zone']) {
                        $line['tariff'] = $resource;
                        break;
                    }
                }

                return $this->renderAjax('_checkDomainLine', [
                    'line' => $line,
                    'state' => 'available',
                ]);
            }


        } else {
            Yii::$app->end();
        }
    }

    /**
     * @return string
     */
    public function actionCheckDomain()
    {
        $model = new Domain();
        $model->scenario = 'check-domain';
        $tariffs = Tariff::find(['scenario' => 'get-available-info'])
            ->joinWith('resources')
            ->andFilterWhere(['type' => 'domain'])
            ->andFilterWhere(['seller' => 'ahnames'])
            ->one();
        $zones = array_filter($tariffs->resources ?: [], function ($resource) {
            return ($resource->zone !== null && $resource->type === Resource::TYPE_DOMAIN_REGISTRATION);
        });
        $dropDownZones = [];
        foreach ($zones as $resource) {
            $dropDownZones[$resource->zone] = '.' . $resource->zone;
        }
        if ($model->load(Yii::$app->request->get())) {
            $domains = [$model->domain . '.' . $model->zone];
            foreach ($dropDownZones as $zone => $label) {
                $domains[] = $model->domain . '.' . $zone;
            }
            $results = [];
            foreach ($domains as $domain) {
                $results[] = [
                    'domain' => $model->domain,
                    'full_domain_name' => $domain,
                    'zone' => substr($domain, strpos($domain, '.') + 1),
                ];
            }
            $results = [reset($results)];// todo: Delete this string
        }

        return $this->render('checkDomain', [
            'model' => $model,
            'dropDownZonesOptions' => $dropDownZones,
            'results' => $results,
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
