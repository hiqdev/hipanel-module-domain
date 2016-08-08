<?php

/*
 * HiSite Domain module
 *
 * @link      https://github.com/hiqdev/hipanel-domain-checker
 * @package   hipanel-domain-checker
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domainchecker\controllers;

use hipanel\actions\SmartPerformAction;
use hipanel\actions\ValidateFormAction;
use hipanel\helpers\StringHelper;
use hipanel\modules\domain\cart\DomainTransferProduct;
use hipanel\modules\domain\models\Domain;
use hiqdev\hiart\Collection;
use hiqdev\hiart\ErrorResponseException;
use hiqdev\yii2\cart\actions\AddToCartAction;
use Yii;
use yii\data\ArrayDataProvider;

class TransferController extends \hipanel\base\CrudController
{
    public function behaviors()
    {
        return []; // todo: remove to enable CRUD
    }

    /**
     * {@inheritdoc}
     */
    public static function newModel($config = [], $submodel = '')
    {
        $config['class'] = Domain::class;

        return Yii::createObject($config);
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'add-to-cart-transfer' => [
                'class' => AddToCartAction::class,
                'productClass' => DomainTransferProduct::class,
                'bulkLoad' => true,
            ],
            'validate-form' => [
                'class' => ValidateFormAction::class,
            ],
            'cancel-transfer' => [
                'class' => SmartPerformAction::class,
                'scenario' => 'only-object',
                'success' => Yii::t('hipanel/domainchecker', 'Domain transfer was canceled'),
            ],
            'reject-transfer' => [
                'class' => SmartPerformAction::class,
                'scenario' => 'only-object',
                'success' => Yii::t('hipanel/domainchecker', 'Domain transfer was rejected'),
            ],
            'approve-transfer' => [
                'class' => SmartPerformAction::class,
                'scenario' => 'only-object',
                'success' => Yii::t('hipanel/domainchecker', 'Domain transfer was approved'),
            ],
            'notify-transfer-in' => [
                'class' => SmartPerformAction::class,
                'scenario' => 'only-object',
                'success' => Yii::t('hipanel/domainchecker', 'FOA was sent'),
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Domain();
        $model->scenario = 'transfer';
        $transferDataProvider = null;
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post($model->formName(), []);
            if (!empty($post[0]['domains'])) {
                $domains = [];
                foreach (StringHelper::explode($post[0]['domains'], "\n") as $line) {
                    preg_match('/^([a-z0-9][0-9a-z.-]+)(?:[,;\s]+)(.*)/i', $line, $matches);
                    if ($matches) {
                        $domain = strtolower($matches[1]);
                        $password = $matches[2];
                        $domains[] = compact('domain', 'password');
                    }
                }
                $post = $domains;
            }

            $collection = (new Collection(['model' => $model]))->load($post);
            $models = $collection->getModels();

            foreach ($models as $model) {
                try {
                    Domain::perform('CheckTransfer', $model->getAttributes(['domain', 'password']));
                } catch (ErrorResponseException $e) {
                    $model->addError('password', $e->getMessage());
                }
            }

            Yii::$app->session->setFlash('transferGrid', 1);
            $transferDataProvider = new ArrayDataProvider(['models' => $models]);
        }

        return $this->render('index', [
            'model' => $model,
            'transferDataProvider' => $transferDataProvider,
        ]);
    }
}
