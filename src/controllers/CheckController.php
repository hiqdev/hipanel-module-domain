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

use hipanel\helpers\ArrayHelper;
use hipanel\modules\domain\forms\BulkCheckForm;
use hipanel\modules\domain\forms\CheckForm;
use hipanel\modules\domain\helpers\DomainSort;
use hipanel\modules\domain\repositories\DomainTariffRepository;
use Yii;
use yii\base\Module;

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
        $model = new CheckForm($zones);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->checkIsAvailable()) {
                foreach ($this->getAvailableZones() as $resource) {
                    if ($resource->zone === $model->zone) {
                        $model->resource = $resource;
                        break;
                    }
                }
            }

            return $this->renderPartial('_checkDomainItem', compact('model'));
        }

        return Yii::$app->end();
    }

    public function actionCheckDomain()
    {
        $results = [];
        $dropDownZonesOptions = $this->getAvailableZonesList();
        $bulkForm = new BulkCheckForm($dropDownZonesOptions);

        if ($bulkForm->load(Yii::$app->request->get(), '') && $bulkForm->validate()) {
            $results = $bulkForm->variateAll();
            $availableZones = $this->getAvailableZones();
            foreach ($results as $model) {
                if ($model->isAvailable) {
                    foreach ($availableZones as $resource) {
                        if ($resource->zone === $model->zone) {
                            $model->resource = $resource;
                            break;
                        }
                    }
                }
            }
        }

        return $this->render('checkDomain', [
            'model' => $bulkForm,
            'dropDownZonesOptions' => $dropDownZonesOptions,
            'results' => $bulkForm->getErrors() ? [] : DomainSort::bySearchQueryTokens($bulkForm->fqdns)->values($results),
        ]);
    }
}
