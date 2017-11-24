<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\controllers;

use hipanel\actions\Action;
use hipanel\actions\IndexAction;
use hipanel\actions\PrepareBulkAction;
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
use hipanel\modules\client\models\Client;
use hipanel\modules\domain\actions\DomainOptionSwitcherAction;
use hipanel\modules\domain\cart\Calculation;
use hipanel\modules\domain\cart\DomainRegistrationProduct;
use hipanel\modules\domain\cart\DomainRenewalProduct;
use hipanel\modules\domain\cart\DomainTransferProduct;
use hipanel\modules\domain\cart\PremiumProduct;
use hipanel\modules\domain\models\Domain;
use hipanel\modules\domain\models\Mailfw;
use hipanel\modules\domain\models\Ns;
use hipanel\modules\domain\models\Park;
use hipanel\modules\domain\models\Urlfw;
use hiqdev\hiart\Collection;
use hiqdev\yii2\cart\actions\AddToCartAction;
use Yii;
use yii\base\DynamicModel;
use yii\base\Event;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Response;

class DomainController extends \hipanel\base\CrudController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'renew-access' => [
                'class' => AccessControl::class,
                'only' => ['add-to-cart-renewal', 'bulk-renewal'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['domain.pay'],
                    ],
                ],
            ],
            'freeze-access' => [
                'class' => AccessControl::class,
                'only' => ['enable-freeze', 'enable-freeze-w-p'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['domain.freeze'],
                    ],
                ],
            ],
            'unfreeze-access' => [
                'class' => AccessControl::class,
                'only' => ['disable-freeze', 'disable-freeze-w-p'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['domain.unfreeze'],
                    ],
                ],
            ],
        ]);
    }

    public function actions()
    {
        return array_merge(parent::actions(), [
            'test' => [
                'class' => RenderAction::class,
            ],
            'add-to-cart-premium' => [
                'class' => AddToCartAction::class,
                'productClass' => PremiumProduct::class,
                'redirectToCart' => true,
            ],
            'add-to-cart-renewal' => [
                'class' => AddToCartAction::class,
                'productClass' => DomainRenewalProduct::class,
                'redirectToCart' => true,
            ],
            'bulk-renewal' => [
                'class' => AddToCartAction::class,
                'productClass' => DomainRenewalProduct::class,
                'bulkLoad' => true,
            ],
            'add-to-cart-registration' => [
                'class' => AddToCartAction::class,
                'redirectToCart' => true,
                'productClass' => DomainRegistrationProduct::class,
            ],
            'add-to-cart-transfer' => [
                'class' => AddToCartAction::class,
                'productClass' => DomainTransferProduct::class,
                'bulkLoad' => true,
            ],
            'domain-push-modal' => [
                'class' => PrepareBulkAction::class,
                'view' => '_modalPush',
                'on beforePerform' => function ($event) {
                    /** @var Action $action */
                    $action = $event->sender;
                    $hasPincode = $this->checkUserHasPincode();

                    $action->data['hasPincode'] = $hasPincode;
                    $action->setScenario($hasPincode ? 'push-with-pincode' : 'push');
                },
            ],
            // Work with contacts
            'bulk-set-contacts-modal' => [
                'class' => PrepareBulkAction::class,
                'scenario' => 'bulk-set-contacts',
                'view' => '_bulkSetContacts',
                'data' => function ($action) {
                    $id = Yii::$app->request->get('id', false);
                    if ($id) {
                        $domainContacts = Domain::perform('get-contacts', ['ids' => [$id]], ['batch' => true]);

                        return [
                            'domainContact' => reset($domainContacts),
                        ];
                    }

                    return [];
                },
            ],
            'bulk-set-contacts' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:domain', 'Contacts is changed'),
                'collectionLoader' => function ($action) {
                    /** @var SmartPerformAction $action */
                    $request = Yii::$app->request;

                    $data = $request->post($action->collection->getModel()->formName());
                    foreach ($data as &$item) {
                        foreach (Domain::$contactTypes as $type) {
                            $item[$type . '_id'] = $request->post($type . '_id');
                        }
                    }
                    $action->collection->load($data);
                },
            ],

            'push' => [
                'class' => SmartPerformAction::class,
                'collectionLoader' => function ($action) {
                    /** @var SmartPerformAction $action */
                    $data = Yii::$app->request->post($action->collection->getModel()->formName());
                    $pincode = $data['pincode'];
                    $receiver = $data['receiver'];
                    unset($data['pincode'], $data['receiver']);
                    foreach ($data as &$item) {
                        $item['pincode'] = $pincode;
                        $item['receiver'] = $receiver;
                    }
                    $action->collection->load($data);
                },
                'POST' => [
                    'save' => true,
                    'success' => [
                        'class' => RedirectAction::class,
                        'url' => function ($action) {
                            return ['@domain'];
                        },
                    ],
                    'error' => [
                        'class' => RedirectAction::class,
                    ],
                ],
                'success' => Yii::t('hipanel:domain', 'Domain was successfully pushed'),
                'error' => Yii::t('hipanel:domain', 'Failed to push the domain'),
            ],
            'index' => [
                'class' => IndexAction::class,
                'filterStorageMap' => [
                    'domain_like' => 'domain.domain.domain_like',
                    'ips' => 'hosting.ip.ip_in',
                    'state' => 'domain.domain.state',
                    'client_id' => 'client.client.id',
                    'seller_id' => 'client.client.seller_id',
                ],
            ],
            'view' => [
                'class' => ViewAction::class,
                'on beforePerform' => function ($event) {
                    $action = $event->sender;
                    $action->getDataProvider()->query
                        ->addSelect(['nsips', 'contacts', 'foa_sent_to', 'mailfws', 'urlfws', 'park', 'premium'])
                        ->joinWith('mailfws')
                        ->joinWith('urlfws')
                        ->joinWith('park')
                        ->joinWith('premium')
                        ->joinWith('registrant')
                        ->joinWith('admin')
                        ->joinWith('tech')
                        ->joinWith('billing');
                },
                'data' => function ($action) {
                    return [
                        'pincodeModel' => new DynamicModel(['pincode']),
                        'hasPincode' => $this->checkUserHasPincode(),
                    ];
                },
            ],
            'validate-form' => [
                'class' => ValidateFormAction::class,
            ],
            'validate-urlfw-form' => [
                'class' => ValidateFormAction::class,
                'model' => Urlfw::class,
            ],
            'validate-mailfw-form' => [
                'class' => ValidateFormAction::class,
                'model' => Mailfw::class,
            ],
            'validate-park-form' => [
                'class' => ValidateFormAction::class,
                'model' => Park::class,
            ],
            'validate-nss' => [
                'class' => ValidateFormAction::class,
                'model' => Ns::class,
                'scenario' => 'default',
                'allowDynamicScenario' => false,
            ],
            'validate-push-form' => [
                'class' => ValidateFormAction::class,
                'collectionLoader' => function ($action) {
                    /** @var SmartPerformAction $action */
                    $request = Yii::$app->request;
                    $action->collection->load([
                        [
                            'pincode' => $request->post('pincode'),
                            'receiver' => $request->post('receiver'),
                        ],
                    ]);
                },
                'validatedInputId' => function ($action, $model, $id, $attribute, $errors) {
                    return 'push-' . $attribute;
                },
            ],
            'set-note' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel', 'Note changed'),
                'error' => Yii::t('hipanel', 'Failed change note'),
                'POST html' => [
                    'save' => true,
                    'success' => [
                        'class' => RedirectAction::class,
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
            'bulk-set-note' => [
                'class' => PrepareBulkAction::class,
                'scenario' => 'set-note',
                'view' => '_bulkSetNote',
            ],
            'set-nss' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel:domain', 'Name servers changed'),
                'POST html' => [
                    'save' => true,
                    'success' => [
                        'class' => RedirectAction::class,
                        'url' => function ($action) {
                            return Yii::$app->request->referrer;
                        },
                    ],
                ],
            ],
            'bulk-set-nss' => [
                'class' => SmartUpdateAction::class,
                'scenario' => 'set-nss',
                'view' => '_bulkSetNs',
                'success' => Yii::t('hipanel:domain', 'Name servers changed'),
                'POST pjax' => [
                    'save' => true,
                    'success' => [
                        'class' => ProxyAction::class,
                        'action' => 'index',
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
                'class' => SmartDeleteAction::class,
                'success' => Yii::t('hipanel:domain', 'Domain deleted'),
                'error' => Yii::t('hipanel:domain', 'Failed delete domain'),
            ],
            'delete-agp' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:domain', 'Domain deleted'),
                'error' => Yii::t('hipanel:domain', 'Failed delete domain'),
            ],
            'cancel-transfer' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:domain', 'Domain transfer was canceled'),
                'error' => Yii::t('hipanel:domain', 'Failed cancel domain transfer'),
            ],
            'reject-transfer' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:domain', 'Domain transfer was cancelled'),
                'error' => Yii::t('hipanel:domain', 'Failed cancel domain transfer'),
                'POST html' => [
                    'save' => true,
                    'success' => [
                        'class' => RedirectAction::class,
                        'url' => function ($action) {
                            $domain = Yii::$app->request->post('Domain');

                            return ['@domain/transfer-cenceled', 'id' => $domain['id']];
                        },
                    ],
                ],
            ],
            'approve-transfer' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:domain', 'Domain transfer was approved'),
                'error' => Yii::t('hipanel:domain', 'Failed approve domain transfer'),
            ],
            'notify-transfer-in' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:domain', 'FOA was sent'),
                'error' => Yii::t('hipanel:domain', 'Failed send FOA'),
            ],
            'enable-hold' => [
                'class' => SmartPerformAction::class,
                'scenario' => 'enable-hold',
                'success' => Yii::t('hipanel:domain', 'Hold was enabled'),
                'error' => Yii::t('hipanel:domain', 'Failed enabling Hold'),
            ],
            'disable-hold' => [
                'class' => SmartPerformAction::class,
                'scenario' => 'disable-hold',
                'success' => Yii::t('hipanel:domain', 'Hold was disabled'),
                'error' => Yii::t('hipanel:domain', 'Failed disabling Hold'),
            ],
            'enable-freeze' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:domain', 'Freeze was enabled'),
            ],
            'disable-freeze' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:domain', 'Freeze was disabled'),
            ],
            'enable-w-p-freeze' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:domain', 'WP Freeze Enabled'),
            ],
            'disable-w-p-freeze' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:domain', 'WP Freeze Disabled'),
            ],
            'OLD-set-ns' => [
                'class' => RenderAction::class,
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
                'class' => SmartPerformAction::class,
                'scenario' => 'set-autorenewal',
                'success' => Yii::t('hipanel:domain', 'Premium autorenewal has been changed'),
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
                'class' => DomainOptionSwitcherAction::class,
                'success' => Yii::t('hipanel:domain', 'Autorenewal has been changed'),
            ],
            'enable-autorenewal' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:domain', 'Autorenewal has been enabled'),
                'scenario' => 'set-autorenewal',
                'on beforeSave' => function (Event $event) {
                    /** @var Action $action */
                    $action = $event->sender;
                    foreach ($action->collection->models as $model) {
                        $model->autorenewal = 1;
                    }
                },
            ],
            'disable-autorenewal' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:domain', 'Autorenewal has been disabled'),
                'scenario' => 'set-autorenewal',
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
                'class' => DomainOptionSwitcherAction::class,
                'success' => Yii::t('hipanel:domain', 'WHOIS protect is changed'),
            ],
            'enable-whois-protect' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:domain', 'WHOIS protect is enabled'),
                'scenario' => 'set-whois-protect',
                'on beforeSave' => function (Event $event) {
                    /** @var Action $action */
                    $action = $event->sender;
                    foreach ($action->collection->models as $model) {
                        $model->enable = 1;
                    }
                },
            ],
            'disable-whois-protect' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:domain', 'WHOIS protect is disabled'),
                'scenario' => 'set-whois-protect',
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
                'class' => DomainOptionSwitcherAction::class,
                'success' => Yii::t('hipanel:domain', 'Lock was changed'),
            ],
            'enable-lock' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:domain', 'Lock was enabled'),
                'scenario' => 'set-lock',
                'on beforeSave' => function (Event $event) {
                    /** @var Action $action */
                    $action = $event->sender;
                    foreach ($action->collection->models as $model) {
                        $model->enable = 1;
                    }
                },
            ],
            'disable-lock' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:domain', 'Lock was disabled'),
                'scenario' => 'set-lock',
                'on beforeSave' => function (Event $event) {
                    /** @var Action $action */
                    $action = $event->sender;
                    foreach ($action->collection->models as $model) {
                        $model->enable = 0;
                    }
                },
            ],
            'regen-password' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:domain', 'The password has been changed'),
            ],
            'sync' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:domain', 'Contacts synced'),
            ],
            'buy' => [
                'class' => RedirectAction::class,
                'url' => Yii::$app->params['organization.url'],
            ],
            'approve-preincoming' => [
                'class' => SmartPerformAction::class,
                'scenario' => 'approve-preincoming',
                'success' => Yii::t('hipanel:domain', 'Successfully approved'),
                'queryOptions' => [
                    'batch' => false,
                ],
                'on beforeSave' => function (Event $event) {
                    Yii::$app->get('hiart')->disableAuth();
                    /** @var Action $action */
                    $action = $event->sender;
                    foreach ($action->collection->models as $model) {
                        $model->confirm_data = JSON::decode($model->confirm_data);
                    }
                },
                'POST html' => [
                    'save' => true,
                    'success' => [
                        'class' => RedirectAction::class,
                        'url' => function ($action) {
                            $domains = Yii::$app->request->post('Domain')['domains'];

                            return ['@domain/preincoming-started', 'domains' => $domains];
                        },
                    ],
                ],
            ],
            'reject-preincoming' => [
                'class' => SmartPerformAction::class,
                'scenario' => 'reject-preincoming',
                'success' => Yii::t('hipanel:domain', 'Successfully rejected'),
                'queryOptions' => [
                    'batch' => false,
                ],
                'on beforeSave' => function (Event $event) {
                    Yii::$app->get('hiart')->disableAuth();
                    /** @var Action $action */
                    $action = $event->sender;
                    foreach ($action->collection->models as $model) {
                        $model->confirm_data = JSON::decode($model->confirm_data);
                    }
                },
                'POST html' => [
                    'save' => true,
                    'success' => [
                        'class' => RedirectAction::class,
                        'url' => function ($action) {
                            $domains = Yii::$app->request->post('Domain')['domains'];

                            return ['@domain/preincoming-canceled', 'domains' => $domains];
                        },
                    ],
                ],
            ],
            'preincoming-started' => [
                'class' => RenderAction::class,
                'view' => 'preincomingStarted',
                'data' => function ($action) {
                    return ['domains' => Yii::$app->request->get('domains')];
                },
            ],
            'preincoming-canceled' => [
                'class' => RenderAction::class,
                'view' => 'preincomingCanseled',
                'data' => function ($action) {
                    return ['domains' => Yii::$app->request->get('domains')];
                },
            ],
            'transfer-canceled' => [
                'class' => ViewAction::class,
                'view' => 'transferCanceled',
            ],
        ]);
    }

    public function actionPremiumDetail($id)
    {
        $apiData = Domain::perform('get-info', ['id' => $id, 'with_seo' => 1, 'with_dns' => 1]);
        $model = Domain::find()->populate([$apiData])[0];

        return $this->renderAjax('_prmiumPackageDetail', ['model' => $model]);
    }

    public function actionTransferOut($id)
    {
        $apiData = Domain::perform('get-info', compact('id'));
        $model = Domain::find()->populate([$apiData])[0];
        if ($model->state !== 'outgoing') {
            throw new Exception('Domain is not pending transfer');
        }

        return $this->render('transferOut', ['model' => $model]);
    }

    public function actionTransferIn($domains, $till_date, $what, $salt, $hash)
    {
        Yii::$app->get('hiart')->disableAuth();
        $data = compact('domains', 'till_date', 'what', 'salt', 'hash');
        $userIP = Yii::$app->request->userIP;
        $model = new Domain($data);
        $model->confirm_data = JSON::encode($data);
        if ($model->hasErrors()) {
            throw new Exception('Params validation has errors.');
        }

        return $this->render('transferIn', compact('model', 'userIP'));
    }

    public function actionRenew()
    {
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionGetPassword()
    {
        $return = ['status' => 'error'];
        $id = Yii::$app->request->post('id');
        $pincode = Yii::$app->request->post('pincode');

        $model = DynamicModel::validateData(compact('id', 'pincode'), array_filter([
            [['id'], 'integer'],
            [['pincode'], 'trim'],
            $this->checkUserHasPincode() ? [['id', 'pincode'], 'required'] : null,
        ]));

        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!$model->hasErrors()) {
            try {
                $return = Domain::perform('get-password', ['id' => $id, 'pincode' => $pincode]);
            } catch (\Exception $e) {
                $return = array_merge($return, ['info' => $e->getMessage()]);
            }
        } else {
            $return = array_merge($return, ['info' => $model->getFirstError('pincode')]);
        }

        return $return;
    }

    public function actionModalNsBody()
    {
        $ids = ArrayHelper::csplit(Yii::$app->request->post('ids'));
        if ($ids) {
            $model = $this->newModel(['scenario' => 'set-ns']);
            $collection = (new Collection(['model' => $model]))->load($model::perform('get-NSs', ArrayHelper::make_sub($ids, 'id'), ['batch' => true]));

            return $this->renderAjax('_modalNsBody', [
                'models' => $collection->models,
            ]);
        } else {
            return Yii::t('hipanel', 'No items selected');
        }
    }

    protected function checkUserHasPincode()
    {
        return Yii::$app->cache->getOrSet(['user-pincode-enabled', Yii::$app->user->id], function () {
            $pincodeData = Client::perform('has-pincode', ['id' => Yii::$app->user->id]);

            return $pincodeData['pincode_enabled'];
        }, 3600);
    }

    public function getRecordForwardingTypes()
    {
        // todo exclude permanent redirect, remain temporary only
        return $this->getRefs('type,forwarding', 'hipanel:domain');
    }

    public function actionGetPremiumPrice($id, $type, $domain)
    {
        if (Yii::$app->request->isAjax) {
            $data = [
                'id' => $id,
                'type' => $type,
                'object' => 'feature',
                'domain' => $domain,
                'expires' => (new \DateTime())->modify('+1 year')->format('c'),
                'amount' => 1,
            ];

            $response = Calculation::perform('calc-value', $data, ['batch' => false]);
            $price = Yii::$app->formatter->asCurrency($response['value']['usd']['value'], 'usd');

            return $price;
        }
    }
}
