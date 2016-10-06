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

use hipanel\modules\domain\models\Domain;
use hipanel\modules\domain\repositories\DomainTariffRepository;
use hipanel\modules\finance\models\DomainResource;
use Yii;

class CheckController extends \hipanel\base\CrudController
{
    private function getAvailableZones()
    {
        /** @var DomainTariffRepository $repository */
        $repository = Yii::createObject(DomainTariffRepository::class);

        return $repository->getAvailableZones();
    }

    public function actionCheck()
    {
        session_write_close();
        Yii::$app->get('hiart')->disableAuth();
        $fqdn = Yii::$app->request->post('domain');
        list($domain, $zone) = explode('.', $fqdn, 2);
        $line = [
            'fqdn' => $fqdn,
            'domain' => $domain,
            'zone' => $zone,
            'resource' => null,
        ];

        if ($fqdn) {
            $check = Domain::perform('Check', ['domains' => [$fqdn]], true);
            if ($check[$fqdn] === 0) {
                $line['isAvailable'] = false;
            } else {
                foreach ($this->getAvailableZones() as $resource) {
                    if ($resource->zone === $zone) {
                        $line['resource'] = $resource;
                        break;
                    }
                }

                $line['isAvailable'] = true;
            }

            return $this->renderAjax('_checkDomainLine', [
                'line' => $line,
            ]);
        } else {
            Yii::$app->end();
        }
    }

    /**
     * @return string
     */
    public function actionCheckDomain()
    {
        $results = [];
        $model = new Domain();
        $model->scenario = 'check-domain';
        $requestedDomain = implode('.', Yii::$app->request->get('Domain') ?: []);
        $zones = $this->getAvailableZones();

        $dropDownZones = [];
        foreach ($zones as $resource) {
            $dropDownZones[$resource->zone] = '.' . $resource->zone;
        }
        uasort($dropDownZones, function ($a, $b) {
            return $a === '.com' ? 0 : 1;
        });
        if ($model->load(Yii::$app->request->get(), '') && !empty($dropDownZones)) {
            // Check if domain already have zone
            if (strpos($model->domain, '.') !== false) {
                list($domain, $zone) = explode('.', $model->domain, 2);
                if (!in_array('.' . $zone, $dropDownZones, true)) {
                    $zone = 'com';
                }
                $model->zone = $zone;
            }

            if ($model->validate()) {
                $requestedDomain = $model->domain . '.' . $model->zone;
                foreach ($dropDownZones as $zone => $label) {
                    $domains[] = $model->domain . '.' . $zone;
                }
                // Make the requestedDomain the first element of array
                $domains = array_diff($domains, [$requestedDomain]);
                array_unshift($domains, $requestedDomain);
                foreach ($domains as $domain) {
                    $results[] = [
                        'fqdn' => $domain,
                        'domain' => $model->domain,
                        'zone' => substr($domain, strpos($domain, '.') + 1),
                    ];
                }
            }
        }
        $model->domain = empty($model->domain) ? Yii::$app->request->get('domain-check') : $model->domain;

        return $this->render('checkDomain', [
            'model' => $model,
            'dropDownZonesOptions' => $dropDownZones,
            'results' => $results,
            'requestedDomain' => $requestedDomain,
        ]);
    }
}
