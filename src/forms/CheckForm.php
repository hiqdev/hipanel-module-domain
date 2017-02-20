<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
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
        if (!in_array($this->getZone(), $this->availableZones, true)) {
            $this->fqdn = $this->getDomain() . '.' . static::DEFAULT_ZONE;
        }
    }

    /**
     * Returns domain zone from the [[fqdn]].
     * @return string
     */
    public function getZone()
    {
        list(, $zone) = explode('.', $this->fqdn, 2);

        return $zone;
    }

    /**
     * Returns domain name from the [[fqdn]].
     * @return string
     */
    public function getDomain()
    {
        list($domain) = explode('.', $this->fqdn, 2);

        return $domain;
    }

    /**
     * Sends API request to check whether domain is available and sets result to [[isAvailable]].
     *
     * @return bool whether domain is available
     */
    public function checkIsAvailable()
    {
        try {
            $check = Domain::perform('check', ['domains' => [$this->fqdn]], ['batch' => true]);
            $this->isAvailable = $check[$this->fqdn] === 1;
        } catch (ResponseErrorException $e) {
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
