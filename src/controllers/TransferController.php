<?php

namespace hipanel\modules\domainchecker\controllers;

use hipanel\actions\SmartPerformAction;
use hipanel\actions\ValidateFormAction;
use hipanel\helpers\StringHelper;
use hipanel\modules\domain\cart\DomainTransferProduct;
use hipanel\modules\domain\models\Domain;
use hipanel\modules\finance\merchant\Collection;
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
     * @inheritdoc
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
                'success' => Yii::t('app', 'Domain transfer was canceled'),
                'error' => Yii::t('app', 'Failed cancel transfer domain'),
            ],
            'reject-transfer' => [
                'class' => SmartPerformAction::class,
                'scenario' => 'only-object',
                'success' => Yii::t('app', 'Domain transfer was rejected'),
                'error' => Yii::t('app', 'Failed reject transfer domain'),
            ],
            'approve-transfer' => [
                'class' => SmartPerformAction::class,
                'scenario' => 'only-object',
                'success' => Yii::t('app', 'Domain transfer was approved'),
                'error' => Yii::t('app', 'Failed approve transfer domain'),
            ],
            'notify-transfer-in' => [
                'class' => SmartPerformAction::class,
                'scenario' => 'only-object',
                'success' => Yii::t('app', 'FOA sended'),
                'error' => Yii::t('app', 'Failed FOA send'),
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
                    preg_match("/^([a-z0-9][0-9a-z.-]+)(?:[,;\s]+)(.*)/i", $line, $matches);
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