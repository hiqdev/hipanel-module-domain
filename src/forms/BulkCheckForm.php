<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\forms;

use hipanel\modules\dns\validators\DomainPartValidator;
use hipanel\modules\domain\logic\DomainVariationsGenerator;
use Yii;
use yii\base\Model;
use yii\helpers\StringHelper;

class BulkCheckForm extends Model
{
    public $fqdns = [];

    /**
     * @var array
     */
    public $zones = [];

    /**
     * @var array
     */
    private $availableZones;

    /**
     * BulkCheckForm constructor.
     * @param array $availableZones
     * @param array $config
     */
    public function __construct($availableZones, array $config = [])
    {
        parent::__construct($config);

        $this->availableZones = $availableZones;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // FQDNs is required field
            [['fqdns'], 'required'],

            // Split FQDNs and Zones (if zones is string)
            [['fqdns', 'zones'], 'filter', 'filter' => function ($value) {
                $domains = [];
                if (is_array($value)) {
                    return $value;
                }
                foreach (StringHelper::explode($value, "\n") as $line) {
                    $domains = array_merge($domains, preg_split('/([,;\s])/i', $line));
                }

                return $domains;
            }],

            // Apply DomainPartValue validator to the split FQDNs
            [['fqdns'], 'each', 'rule' => [DomainPartValidator::class, 'enableIdn' => true]],
            [['fqdns'], 'filter', 'filter' => function ($value) {
                return array_map('mb_strtolower', $value);
            }],

            // Check split FQDNs to zone is allowed
            [['fqdns'], 'filter', 'filter' => function ($value) {
                return array_filter($value, function ($value) {
                    list($fqdn, $zone) = explode('.', $value, 2);

                    if (empty($zone)) {
                        return $value;
                    }

                    $zoneIsAvailable = in_array($zone, array_keys($this->availableZones), true);

                    if (!$zoneIsAvailable) {
                        return $fqdn;
                    }

                    return $zoneIsAvailable;
                });
            }],
            // Check if zones is available
            [['zones'], 'filter', 'filter' => function ($zones) {
                return array_filter((array) $zones, function ($zone) {
                    return in_array($zone, array_keys($this->availableZones), true);
                });
            }, 'skipOnEmpty' => true],
        ];
    }

    public function getFqdnsInline()
    {
        return $this->fqdns
            ? implode(' ', array_map(function($value) {
                    return DomainPartValidator::convertAsciiToIdn($value);
                }, $this->fqdns))
            : '';
    }

    public function getData()
    {
        $result = [];
        $ifSingleZone = !empty($this->zones) && count($this->zones) === 1 ? reset($this->zones) : null;
        foreach ($this->fqdns as $row) {
            $result[] = ['fqdn' => $row, 'zone' => $ifSingleZone];
        }

        return $result;
    }

    public function attributeLabels()
    {
        return [
            'fqdns' => Yii::t('hipanel:domain', 'Domains'),
            'zones' => Yii::t('hipanel:domain', 'Zones'),
        ];
    }

    public function adjustZonesFor(CheckForm $checkForm)
    {
        // Single domain, no zones selected
        //  => all zones
        if (count($this->fqdns) === 1 && count($this->zones) === 0) {
            if ($singleZoneWithDomain = $checkForm->getZone()) {
                $zones = array_diff_key($this->availableZones, [$singleZoneWithDomain => '']);

                return [$singleZoneWithDomain => '.' . $singleZoneWithDomain] + $zones;
            }

            return $this->availableZones;
        }

        // Many domains or many zones
        //  => Intersection between selected and available zones
        $zones = array_intersect_key($this->availableZones, array_flip((array) $this->zones));

        // In case there is a zone in the domain
        //  => Add it to the zones array
        if ($checkForm->getZone()) {
            $zones[$checkForm->getZone()] = $checkForm->availableZones[$checkForm->getZone()];
        }

        // In case there are no selected zones and domain does not contain zone
        //  => all zones
        if (empty($zones)) {
            $zones = $this->availableZones;
        }

        return $zones;
    }

    public function variate($fqdn)
    {
        $form = new CheckForm($this->availableZones);
        $form->fqdn = $fqdn;

        $generator = new DomainVariationsGenerator($form->getDomain(), $form->getZone(), $this->adjustZonesFor($form));

        return $generator->run();
    }

    public function variateAll()
    {
        $results = [];

        foreach ($this->fqdns as $fqdn) {
            $results = array_merge($results, $this->variate($fqdn));
        }

        return $results;
    }
}
