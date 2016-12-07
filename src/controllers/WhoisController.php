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
        $whoisDefault = ['domain' => $domain];
        try {
            $apiData = Yii::$app->hiart->createCommand()->perform('domainGetWhois', ['domain' => $domain]);
        } catch (Exception $e) {
            $apiData['message'] = $e->getMessage();
        }
        if ($apiData['registrar'] || is_array($apiData['nss'])) {
            $whoisDefault['availability'] = Whois::REGISTRATION_UNAVAILABLE;
        }
        if ($apiData['message'] === 'domain available') {
            $whoisDefault['availability'] = Whois::REGISTRATION_AVAILABLE;
        }
        if ($apiData['message'] === 'domain unsupported' || ($apiData['rawdata'] && strstr(reset($apiData['rawdata']), 'domain is not supported'))) {
            $whoisDefault['availability'] = Whois::REGISTRATION_UNSUPPORTED;
        }

        $model = reset(Whois::find()->populate([array_merge($whoisDefault, $apiData)]));

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
