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

use hipanel\helpers\ArrayHelper;
use hipanel\modules\domain\forms\CheckForm;
use hipanel\modules\domain\logic\DomainVariationsGenerator;
use hipanel\modules\domain\repositories\DomainTariffRepository;
use Yii;
use yii\base\Module;
use yii\web\NotFoundHttpException;

class CheckController extends \hipanel\base\CrudController
{
    /**
     * @var DomainTariffRepository
     */
    protected $domainTariffRepository;

    public $defaultAction = 'check-domain';

    public function __construct($id, Module $module, DomainTariffRepository $domainTariffRepository, array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->domainTariffRepository = $domainTariffRepository;
    }

    private function getAvailableZones()
    {
        $zones = $this->domainTariffRepository->getAvailableZones();

        if ($zones === []) {
            throw new NotFoundHttpException('No available domain zones found.');
        }

        return $zones;
    }

    private function getAvailableZonesList()
    {
        return ArrayHelper::map($this->getAvailableZones(), 'zone', function ($resource) {
            return '.' . $resource->zone;
        });
    }

    public function actionCheck()
    {
        session_write_close();
        Yii::$app->get('hiart')->disableAuth();

        $zones = $this->getAvailableZonesList();
        $model = new CheckForm(array_keys($zones));

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->checkIsAvailable()) {
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
        $zones = $this->getAvailableZonesList();

        $model = new CheckForm(array_keys($zones));

        if ($model->load(Yii::$app->request->get()) && $model->validate() && !empty($model->fqdn)) {
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
