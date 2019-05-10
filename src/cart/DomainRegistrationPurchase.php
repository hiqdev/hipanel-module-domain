<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\cart;

use Yii;

class DomainRegistrationPurchase extends AbstractDomainPurchase
{
    public $registrant;

    public function init()
    {
        parent::init();

        $this->registrant = $this->position->registrant;
    }

    /** {@inheritdoc} */
    public static function operation()
    {
        return 'Register';
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['registrant'], 'integer'],
        ]);
    }

    /** {@inheritdoc} */
    public function renderNotes()
    {
        $date = (new \DateTime())->add(new \DateInterval('P' . $this->amount . 'Y'));

        return Yii::t('hipanel:domain', 'The domain name was registered till {date}', [
            'date' => Yii::$app->formatter->asDate($date),
        ]);
    }

    public function getPurchasabilityRules()
    {
        return [
            DomainContactsCompatibilityValidator::class,
        ];
    }
}
