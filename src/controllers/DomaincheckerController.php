<?php

namespace hipanel\modules\domainchecker\controllers;

use hipanel\modules\domain\models\Domain;
use hipanel\modules\finance\models\Resource;
use hipanel\modules\finance\models\Tariff;
use Yii;

class DomaincheckerController extends \hipanel\base\CrudController
{
    public function actionCheck()
    {
        session_write_close();
        Yii::$app->hiresource->auth = function () {
            return [];
        };
        $fqdn = Yii::$app->request->post('domain');
        list($domain, $zone) = explode('.', $fqdn, 2);
        $line = [
            'fqdn' => $fqdn,
            'domain' => $domain,
            'zone' => $zone,
            'resource' => null,
        ];

        if ($fqdn) {
//            $check = Domain::perform('Check', ['domains' => [$fqdn]], true);
            $check = [$domain => mt_rand(0,1)]; // todo: remove this line
            if ($check[$fqdn] === 0) {
                $line['isAvailable'] = false;
            } else {
                $tariff = $this->getDomainTariff();
                $zones = $this->getDomainZones($tariff, Resource::TYPE_DOMAIN_REGISTRATION);

                foreach ($zones as $resource) {
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

        $tariff = $this->getDomainTariff();
        $zones = $this->getDomainZones($tariff, Resource::TYPE_DOMAIN_REGISTRATION);

        $dropDownZones = [];
        foreach ($zones as $resource) {
            $dropDownZones[$resource->zone] = '.' . $resource->zone;
        }
        uasort($dropDownZones, function ($a, $b) { return $a === '.com' ? 0 : 1; });
        if ($model->load(Yii::$app->request->get()) && !empty($dropDownZones)) {
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

        return $this->render('checkDomain', [
            'model' => $model,
            'dropDownZonesOptions' => $dropDownZones,
            'results' => $results,
        ]);
    }

    /**
     * Returns the tariff for the domain operations
     * Caches the API request for 3600 seconds and depends on client id and seller login.
     * @throws \yii\base\InvalidConfigException
     * @return Tariff
     */
    protected function getDomainTariff()
    {
        $params = [
            Yii::$app->user->isGuest ? Yii::$app->params['user.seller'] : Yii::$app->user->identity->seller,
            Yii::$app->user->isGuest ? null : Yii::$app->user->id,
        ];

        return Yii::$app->get('cache')->getTimeCached(3600, $params, function ($seller, $client_id) {
            return Tariff::find(['scenario' => 'get-available-info'])
                ->joinWith('resources')
                ->andFilterWhere(['type' => 'domain'])
                ->andFilterWhere(['seller' => $seller])
                ->one();
        });
    }

    /**
     * @param Tariff $tariff
     * @param string $type
     * @return array
     */
    protected function getDomainZones($tariff, $type = Resource::TYPE_DOMAIN_REGISTRATION)
    {
        if ($tariff === null || !$tariff instanceof Tariff) {
            return [];
        }

        return array_filter((array) $tariff->resources, function ($resource) use ($type) {
            return $resource->zone !== null && $resource->type === $type;
        });
    }
}