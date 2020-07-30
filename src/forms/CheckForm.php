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
use hipanel\modules\domain\models\Domain;

use hiqdev\hiart\ResponseErrorException;
use Yii;
use yii\base\Model;

/**
 * Class CheckForm.
 *
 * @property string $fqdn fully-qualified domain name
 * @property string $domain domain name
 * @property string $zone domain zone
 */
class CheckForm extends Model
{
    const DEFAULT_ZONE = Domain::DEFAULT_ZONE;

    /**
     * @var string fully qualified domain name
     */
    public $fqdn;

    /**
     * @var bool whether domain is available
     */
    public $isAvailable;

    /**
     * @var bool whether domain is premium
     */
    public $isPremium;

    /**
     * @var array of check data
     */
    public $checkData;

    /**
     * @var bool whether obj is suggestion
     */
    public $isSuggestion = false;

    /**
     * @var resource
     */
    public $resource;

    /**
     * @var array available domain zones
     */
    public $availableZones;

    public function __construct($availableZones, $config = [])
    {
        parent::__construct($config);

        $this->availableZones = $availableZones;
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'fqdn',
            'isAvailable',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function load($data, $formName = '')
    {
        if (strlen($data['fqdn']) && strpos($data['fqdn'], '.') === false) {
            if (strlen($data['zone'])) {
                $data['fqdn'] .= '.' . $data['zone'];
                unset($data['zone']);
            } else {
                $data['fqdn'] .= '.' . static::DEFAULT_ZONE;
            }
        }

        return parent::load($data, $formName);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fqdn'], DomainPartValidator::class, 'enableIdn' => true, 'mutateAttribute' => false, 'message' => Yii::t('hipanel:domain', 'Domain name is invalid')],
            [['fqdn'], 'zoneIsAllowed'],
            [['fqdn'], 'required'],
        ];
    }

    public function zoneIsAllowed()
    {
        if (!in_array($this->getZone(), array_keys($this->availableZones), true)) {
            $this->fqdn = $this->getDomain() . '.' . static::DEFAULT_ZONE;
        }
    }

    /**
     * Returns domain zone from the [[fqdn]].
     * @return null|string
     */
    public function getZone(): ?string
    {
        list(, $zone) = explode('.', $this->fqdn, 2);

        return $zone;
    }

    /**
     * Returns domain name from the [[fqdn]].
     * @return null|string
     */
    public function getDomain(): ?string
    {
        list($domain) = explode('.', $this->fqdn, 2);

        return $domain;
    }

    /**
     * Returns domain name from the [[fqdn]] in IDN
     * @return null|string
     */
    public function getDomainIDN(): ?string
    {
        return DomainPartValidator::convertAsciiToIdn($this->getDomain());
    }

    /**
     * @return string|null
     */
    public function getZoneIDN(): ?string
    {
        return DomainPartValidator::convertAsciiToIdn($this->getZone());
    }

    /**
     * check whether domain is available and sets result to [[isAvailable]].
     *
     * @return bool whether domain is available
     */
    public function checkIsAvailable()
    {
        if ($this->checkData === null) {
            $this->sendRequest();
        }

        $this->isAvailable = $this->checkData['is_available'];
        return $this->isAvailable;
    }

    /**
     * check whether domain is premium and sets result to [[isPremium]].
     *
     * @return bool whether domain is available
     */
    public function checkIsPremium()
    {
        if ($this->checkData === null) {
            $this->sendRequest();
        }

        $this->isPremium = $this->checkData['is_premium'];
        return $this->isPremium;
    }

    public function checkDomain() {
        $this->checkIsPremium();

        return $this->checkIsAvailable();
    }

    public function attributeLabels()
    {
        return [
            'fqdn' => Yii::t('hipanel:domain', 'Domain'),
        ];
    }

    /**
     * Send API request to API
     *
     * @return array
     */
    public function sendRequest()
    {
        $domain = DomainPartValidator::convertAsciiToIdn(mb_strtolower($this->fqdn));
        $this->checkData = Yii::$app->cache->getOrSet([__CLASS__, __METHOD__, $domain], function () use ($domain) {
            try {
                $check = Domain::perform('check-premium', ['domains' => [$domain]], ['batch' => true]);
                return [
                    'is_available' => isset($check[$domain]['avail']) && $check[$domain]['avail'] === 1,
                    'is_premium' => isset($check[$domain]['premium']) && $check[$domain]['premium'] === 1,
                ];
            } catch (ResponseErrorException $e) {
                return [
                    'is_available' => false,
                    'is_premium' => false,
                ];
            }
        }, 60);

        return $this->checkData;
    }
}
