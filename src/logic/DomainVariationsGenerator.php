<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\logic;

use hipanel\helpers\ArrayHelper;
use hipanel\modules\domain\forms\CheckForm;
use hipanel\modules\domain\models\Domain;
use Yii;

/**
 * Class DomainVariationsGenerator provides a simple API to generate domain name variations
 * using the input domain name and a list of available zones.
 *
 * Usage example:
 *
 * ```php
 * $generator = new DomainVariationsGenerator('example', 'com', [
 *     'com' => 'Commercial domain', 'me' => 'Personal domains'
 * ]);
 *
 * $generator->run(); // returns array of 2 CheckForm models: for `example.com` and `example.net` respectively.
 *
 * ```
 */
class DomainVariationsGenerator
{
    /**
     * @var string the domain name
     */
    protected $domain;

    /**
     * @var string the domain name zone
     */
    protected $zone;

    /**
     * @var array of available zones.
     * Key - zone name (`com`, `net`, etc)
     * Value - zone title (`Commercial domains`, etc). Optional, is not in use at least for now
     */
    protected $availableZones;

    /**
     * DomainVariationsGenerator constructor.
     *
     * @param string $domain The domain name. See [[domain]] description for details.
     * @param string $zone The zone. See [[zone]] description for details.
     * @param array $availableZones Available domain zones. See [[availableZones]] description for details.
     */
    public function __construct($domain, $zone, $availableZones)
    {
        $this->domain = $domain;
        $this->zone = $zone;
        $this->availableZones = $availableZones;
    }

    /**
     * @return string the fully-qualified domain name form [[domain]] and [[zone]]
     */
    public function getFqdn()
    {
        return implode('.', [$this->domain, $this->zone]);
    }

    /**
     * @return CheckForm[] the domain name variations models
     */
    public function run()
    {
        $domains = $this->generateVariations();
        $suggestions = $this->generateSuggestions();
        foreach ($suggestions as $suggestion) {
            $key = array_search(strtolower($suggestion['fqdn']), $domains);
            if ($key === false) {
                array_push($domains, $suggestion);
            } else {
                unset($suggestion['isSuggestion']);
                $domains[$key] = array_map('strtolower', $suggestion);
            }
        }
        $this->orderVariations($domains);

        return $this->buildModels($domains);
    }

    /**
     * @param $variations array of domain name variations. See [[generateVariations]] for details.
     * @return CheckForm[] the domain name variations models
     */
    protected function buildModels($variations)
    {
        $results = [];

        foreach ($variations as $domain) {
            if (is_array($domain)) {
                $results[] = new CheckForm(array_keys($this->availableZones), $domain);
            } else {
                $results[] = new CheckForm(array_keys($this->availableZones), ['fqdn' => $domain]);
            }
        }

        return $results;
    }

    /**
     * Generates variations form the [[domain]] using [[availableZones]]
     * You can re-define this method to get different variations.
     * @return array of domain zone variations.
     *  - Key - integer index
     *  - Value - the variation itself
     */
    protected function generateVariations()
    {
        $variations = [];

        foreach ($this->availableZones as $zone => $label) {
            $variations[] = $this->domain . '.' . $zone;
        }

        return $variations;
    }

    protected function generateSuggestions()
    {
        $variations = [];
        $apiData = Domain::perform('get-suggestions', ['name' => $this->domain, 'zones' => $this->zone]);
        if (isset($apiData['results'])) {
            foreach ($apiData['results'] as $row) {
                if ($row['availability'] === 'available') {
                    $variations[] = [
                        'fqdn' => $row['name'],
                        'isAvailable' => ($row['availability'] === 'available'),
                        'isSuggestion' => true,
                    ];
                }
            }
        }

        return $variations;
    }

    /**
     * Orders $variations to have the originally queried domain on top of the list.
     *
     * @param array $variations
     */
    protected function orderVariations(&$variations)
    {
        if (in_array($this->getFqdn(), $variations, true)) {
            $variations = array_diff($variations, [$this->getFqdn()]);
            array_unshift($variations, $this->getFqdn());
        }
    }
}
