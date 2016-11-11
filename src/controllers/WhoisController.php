<?php

namespace hipanel\modules\domain\controllers;

use hipanel\helpers\ArrayHelper;
use hipanel\modules\domain\repositories\DomainTariffRepository;
use hipanel\modules\domain\models\Whois;
use yii\base\Exception;
use yii\web\UnprocessableEntityHttpException;
use Yii;

class WhoisController extends \hipanel\base\CrudController
{
    private function getWhoisModel($domain)
    {
        $whois = ['domain' => $domain, 'available' => false, 'unsupported' => false];
        $message = '';
        try {
            $whois = Yii::$app->hiart->createCommand()->perform('domainGetWhois', ['domain' => $domain]);
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        if ($message === 'domain available') {
            $whois['available'] = true;
        } elseif ($message === 'domain unsupported' || strstr(reset($whois['rawdata']), 'domain is not supported')) {
            $whois['unsupported'] = true;
        }
        $model = reset(Whois::find()->populate([$whois]));

        return $model;
    }

    public function actionIndex($domain = null)
    {
        $model = new Whois;
        $model->load(Yii::$app->request->get(), '');
        if (!$model->validate()) {
            throw new UnprocessableEntityHttpException();
        }
        /** @var DomainTariffRepository $repository */
        $repository = Yii::createObject(DomainTariffRepository::class);
        $availableZones = ArrayHelper::getColumn($repository->getAvailableZones(), 'zone', false);

        return $this->render('index', [
            'model' => $model,
            'availableZones' => $availableZones,
        ]);
    }

    public function actionLookup()
    {
        $request = Yii::$app->request;
        $model = $this->getWhoisModel($request->post('domain'));
        if ($request->isAjax) {
            return $this->renderAjax('_view', [
                'model' => $model,
            ]);
        }

        Yii::$app->end();
    }
}
