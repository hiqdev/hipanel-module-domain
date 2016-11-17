<?php

namespace hipanel\modules\domain\forms;

use Guzzle\Plugin\ErrorResponse\Exception\ErrorResponseException;
use hipanel\modules\dns\validators\DomainPartValidator;
use hipanel\modules\domain\models\Domain;
use Yii;
use yii\base\Model;

/**
 * Class CheckForm
 *
 * @property string $fqdn fully-qualified domain name
 * @property string $domain domain name
 * @property string $zone domain zone
 *
 * @package hipanel\modules\domain\forms
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
     * @var Resource
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
     * @inheritdoc
     */
    public function load($data, $formName = '')
    {
        if (strpos($data['fqdn'], '.') === false) {
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
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fqdn'], DomainPartValidator::class, 'enableIdn' => true, 'mutateAttribute' => false, 'message' => Yii::t('hipanel:domain', 'Domain name is invalid')],
            [['fqdn'], 'zoneIsAllowed'],
        ];
    }

    public function zoneIsAllowed()
    {
        if (!in_array($this->getZone(), $this->availableZones)) {
            $this->fqdn = $this->getDomain() . '.' . static::DEFAULT_ZONE;
        }
    }

    /**
     * Returns domain zone from the [[fqdn]]
     * @return string
     */
    public function getZone()
    {
        list(, $zone) = explode('.', $this->fqdn, 2);

        return $zone;
    }

    /**
     * Returns domain name from the [[fqdn]]
     * @return string
     */
    public function getDomain()
    {
        list($domain,) = explode('.', $this->fqdn, 2);

        return $domain;
    }

    /**
     * Sends API request to check whether domain is available and sets result to [[isAvailable]]
     *
     * @return bool whether domain is available
     */
    public function checkIsAvailable()
    {
        try {
            $check = Domain::perform('Check', ['domains' => [$this->fqdn]], true);
            $this->isAvailable = $check[$this->fqdn] === 1;
        } catch (ErrorResponseException $e) {
            $this->isAvailable = false;
        }

        return $this->isAvailable;
    }

    public function attributeLabels()
    {
        return [
            'fqdn' => Yii::t('hipanel:domain', 'Domain'),
        ];
    }
}
