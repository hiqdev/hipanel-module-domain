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
use hiqdev\hiart\ResponseErrorException;
use Yii;

/**
 * Class DomainVariationsGenerator provides a simple API to generate domain name variations and suggestions
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
        $variations = $this->generateZoneVariations();
        $this->orderVariations($variations);
        $suggestions = $this->generateSuggestions();
        $this->removeDuplicates($variations, $suggestions);

        return array_merge($variations, $suggestions);
    }

    /**
     * Removes items from $suggestions array that are already present in $variations array.
     *
     * @param CheckForm[] $variations
     * @param CheckForm[] $suggestions passed by reference
     */
    private function removeDuplicates($variations, &$suggestions)
    {
        $domainNames = ArrayHelper::getColumn($variations, 'fqdn');

        $suggestions = array_filter($suggestions, function (CheckForm $suggestion) use ($domainNames) {
            return array_search(strtolower($suggestion->fqdn), $domainNames) === false;
        });
    }

    /**
     * @param array $attributes array of check form attributes
     * @return CheckForm the domain name variations model
     */
    protected function buildModel(array $attributes)
    {
       return new CheckForm(array_keys($this->availableZones), $attributes);
    }

    /**
     * Generates variations form the [[domain]] using [[availableZones]]
     * You can re-define this method to get different variations.
     * @return CheckForm[] of domain zone variations.
     *  - Key - integer index
     *  - Value - the [[CheckForm]] object
     */
    protected function generateZoneVariations()
    {
        $variations = [];

        foreach ($this->availableZones as $zone => $label) {
            $variations[] = $this->buildModel(['fqdn' => $this->domain . '.' . $zone]);
        }

        return $variations;
    }

    /**
     * Fetches domain name suggestions from suggestion-generation API and returns [[CheckForm]] objects.
     *
     * @return CheckForm[]
     */
    protected function generateSuggestions()
    {
        try {
            $apiData = Domain::perform('get-suggestions', ['name' => $this->domain, 'zones' => $this->zone]);
        } catch (ResponseErrorException $e) {
            Yii::error("Failed to generate suggestions: {$e->getMessage()}", __METHOD__);
            return [];
        }

        if (!isset($apiData['results'])) {
            return [];
        }

        $suggestions = [];
        foreach ($apiData['results'] as $row) {
            if ($row['availability'] === 'available') {
                $suggestions[] = $this->buildModel([
                    'fqdn' => $row['name'],
                    'isAvailable' => $row['availability'] === 'available',
                    'isSuggestion' => true,
                ]);
            }
        }

        return $suggestions;
    }

    /**
     * Orders $variations to have the originally queried domain on top of the list.
     *
     * @param array $variations
     */
    protected function orderVariations(&$variations)
    {
        usort($variations, function (CheckForm $prev, CheckForm $curr) {
            return $curr->fqdn === $this->getFqdn() ? 1 : 0;
        });
    }
}
