<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\controllers;

use hipanel\actions\SmartPerformAction;
use hipanel\actions\ValidateFormAction;
use hipanel\filters\EasyAccessControl;
use hipanel\helpers\StringHelper;
use hipanel\modules\domain\cart\DomainTransferProduct;
use hipanel\modules\domain\models\Domain;
use hiqdev\hiart\Collection;
use hiqdev\hiart\ResponseErrorException;
use hiqdev\yii2\cart\actions\AddToCartAction;
use Yii;
use yii\data\ArrayDataProvider;

class TransferController extends \hipanel\base\CrudController
{
    /**
     * {@inheritdoc}
     */
    public static function newModel($config = [], $submodel = '')
    {
        $config['class'] = Domain::class;

        return Yii::createObject($config);
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => EasyAccessControl::class,
                'actions' => [
                    'add-to-cart-transfer' => true,
                    'validate-form' => true,
                    'index' => true,
                    '*' => 'domain.read',
                ],
            ],
        ]);
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
                'success' => Yii::t('hipanel:domain', 'Domain transfer was canceled'),
            ],
            'reject-transfer' => [
                'class' => SmartPerformAction::class,
                'scenario' => 'only-object',
                'success' => Yii::t('hipanel:domain', 'Domain transfer was rejected'),
            ],
            'approve-transfer' => [
                'class' => SmartPerformAction::class,
                'scenario' => 'only-object',
                'success' => Yii::t('hipanel:domain', 'Domain transfer was approved'),
            ],
            'notify-transfer-in' => [
                'class' => SmartPerformAction::class,
                'scenario' => 'only-object',
                'success' => Yii::t('hipanel:domain', 'FOA was sent'),
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
                    preg_match('/^(([a-zа-я0-9][0-9a-zа-я\-]+\.)+([a-zа-я0-9][0-9a-zа-я\-]+))(?:[,;\s]+)(.*)$/ui', $line, $matches);
                    if ($matches) {
                        $domain = strtolower($matches[1]);
                        $password = $matches[4];
                        $domains[] = compact('domain', 'password');
                    }
                }
                $post = $domains;
            }

            $collection = (new Collection(['model' => $model]))->load($post);
            $models = $collection->getModels();

            foreach ($models as $model) {
                try {
                    Domain::perform('check-transfer', $model->getAttributes(['domain', 'password']));
                } catch (ResponseErrorException $e) {
                    $model->addError('password', $e->getMessage());
                }
            }

            Yii::$app->session->setFlash('transferGrid', 1);
            $transferDataProvider = new ArrayDataProvider([
                'allModels' => $models,
                'pagination' => false,
                'sort' => false,
                'modelClass' => Domain::class,
            ]);
        }

        return $this->render('index', [
            'model' => $model,
            'transferDataProvider' => $transferDataProvider,
        ]);
    }

    public function actionTransfer()
    {
        $model = new Domain();
        $model->scenario = 'transfer';
        $transferDataProvider = null;
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post($model->formName(), []);
            if (!empty($post[0]['domains'])) {
                $domains = [];
                foreach (StringHelper::explode($post[0]['domains'], "\n") as $line) {
                    preg_match('/(([a-zа-я0-9][0-9a-zа-я\-]+\.)+([a-zа-я0-9][0-9а-яa-z\-]+))(?:[,;\s]+)(.*)/ui', $line, $matches);
                    if ($matches) {
                        $domain = strtolower($matches[1]);
                        $password = $matches[4];
                        $domains[] = compact('domain', 'password');
                    }
                }
                $post = $domains;
            }

            $collection = (new Collection(['model' => $model]))->load($post);
            $models = $collection->getModels();

            foreach ($models as $model) {
                try {
                    Domain::perform('check-transfer', $model->getAttributes(['domain', 'password']));
                } catch (ResponseErrorException $e) {
                    $model->addError('password', $e->getMessage());
                }
            }

            Yii::$app->session->setFlash('transferGrid', 1);
            $transferDataProvider = new ArrayDataProvider([
                'allModels' => $models,
                'pagination' => false,
                'sort' => false,
                'modelClass' => Domain::class,
            ]);
        }

        return $this->render('index', [
            'models' => $models,
            'model' => $model,
            'transferDataProvider' => $transferDataProvider,
        ]);
    }

    /**
     * @param $models
     * @return bool
     */
    public function isButtonDisabled(array $models)
    {
        foreach ($models as $model) {
            if ($model instanceof Domain) {
                /** @var Domain $model */
                $errors = $model->getErrors();
                if (empty($errors)) {
                    return false;
                }
            }
        }

        return true;
    }
}
