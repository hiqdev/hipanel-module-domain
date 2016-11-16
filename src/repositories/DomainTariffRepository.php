<?php

namespace hipanel\modules\domain\repositories;

use hipanel\helpers\ArrayHelper;
use hipanel\modules\domain\models\Domain;
use hipanel\modules\finance\models\DomainResource;
use hipanel\modules\finance\models\Resource;
use hipanel\modules\finance\models\Tariff;
use Yii;
use yii\base\InvalidConfigException;

class DomainTariffRepository
{
    /**
     * Returns the tariff for the domain operations
     * Caches the API request for 3600 seconds and depends on client id and seller login.
     * @throws \yii\base\InvalidConfigException
     * @return Tariff
     */
    public function getTariff()
    {
        if (Yii::$app->user->isGuest) {
            if (isset(Yii::$app->params['user.seller'])) {
                $params = [
                    Yii::$app->params['user.seller'],
                    null
                ];
            } else throw new InvalidConfigException('"seller" is must be set');
        } else {
            $params = [
                Yii::$app->user->identity->seller,
                Yii::$app->user->id,
            ];
        }

        return Yii::$app->getCache()->getTimeCached(3600, $params, function ($seller, $client_id) {
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
     * @param bool $orderByDefault whether to order zones by default zone
     * @see orderZones
     * @return array
     */
    public function getZones($tariff, $type = DomainResource::TYPE_DOMAIN_REGISTRATION, $orderByDefault = true)
    {
        if ($tariff === null || !$tariff instanceof Tariff) {
            return [];
        }

        $resources = array_filter((array)$tariff->resources, function ($resource) use ($type) {
            return $resource->zone !== null && $resource->type === $type;
        });

        if ($orderByDefault) {
            return $this->orderZones($resources);
        }

        return $resources;
    }

    public function getAvailableZones()
    {
        $tariff = $this->getTariff();

        return $this->getZones($tariff);
    }

    /**
     * @param Resource[] $zones array of domain resources to be sorted
     * @return array sorted by the default zone resources
     */
    public function orderZones($zones)
    {
        $result = ArrayHelper::index($zones, 'zone');

        uasort($result, function ($a, $b) {
            return $a->zone === Domain::DEFAULT_ZONE;
        });

        return $result;
    }
}
