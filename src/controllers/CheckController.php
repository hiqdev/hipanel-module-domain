<?php

/*
 * HiSite Domain module
 *
 * @link      https://github.com/hiqdev/hipanel-domain-checker
 * @package   hipanel-domain-checker
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\controllers;

use hipanel\helpers\ArrayHelper;
use hipanel\modules\domain\forms\CheckForm;
use hipanel\modules\domain\logic\DomainVariationsGenerator;
use hipanel\modules\domain\repositories\DomainTariffRepository;
use Yii;
use yii\base\Module;

class CheckController extends \hipanel\base\CrudController
{
    /**
     * @var DomainTariffRepository
     */
    protected $domainTariffRepository;

    public function __construct($id, Module $module, DomainTariffRepository $domainTariffRepository, array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->domainTariffRepository = $domainTariffRepository;
    }

    private function getAvailableZones()
    {
        return $this->domainTariffRepository->getAvailableZones();
    }

    public function actionCheck()
    {
        session_write_close();
        Yii::$app->get('hiart')->disableAuth();

        $model = new CheckForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($available = $model->checkIsAvailable()) {
                foreach ($this->getAvailableZones() as $resource) {
                    if ($resource->zone === $model->zone) {
                        $model->resource = $resource;
                        break;
                    }
                }
            }

            return $this->renderAjax('_checkDomainItem', [
                'model' => $model,
            ]);
        }

        return Yii::$app->end();
    }

    public function actionCheckDomain()
    {
        $results = [];
        $model = new CheckForm();
        $zones = ArrayHelper::map($this->getAvailableZones(), 'zone', function ($resource) {
            return '.' . $resource->zone;
        });

        if ($model->load(Yii::$app->request->get()) && $model->validate()) {
            $generator = new DomainVariationsGenerator($model->getDomain(), $model->getZone(), $zones);
            $results = $generator->run();
        }

        return $this->render('checkDomain', [
            'model' => $model,
            'dropDownZonesOptions' => $zones,
            'results' => $results,
        ]);
    }
}
