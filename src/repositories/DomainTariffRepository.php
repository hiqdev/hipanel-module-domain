<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\repositories;

use hipanel\helpers\ArrayHelper;
use hipanel\modules\domain\models\Domain;
use hipanel\modules\finance\models\DomainResource;
use hipanel\modules\finance\models\Tariff;
use yii\base\Application;

class DomainTariffRepository
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Returns the tariff for the domain operations
     * Caches the API request for 3600 seconds and depends on client id and seller login.
     * @return Tariff
     */
    public function getTariff()
    {
        if ($this->app->user->isGuest) {
            $seller = $this->app->user->seller;
            $client_id = null;
        } else {
            $seller = $this->app->user->identity->seller;
            $client_id = $this->app->user->id;
        }

        return $this->app->get('cache')->getOrSet([__METHOD__, $seller, $client_id], function () use ($seller, $client_id) {
            return Tariff::find()
                ->action('get-available-info')
                ->joinWith('resources')
                ->andFilterWhere(['type' => 'domain'])
                ->andFilterWhere(['seller' => $seller])
                ->andWhere(['with_resources' => true])
                ->one();
        }, 3600);
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

        $resources = array_filter((array) $tariff->resources, function ($resource) use ($type) {
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
