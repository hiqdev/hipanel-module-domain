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

/**
 * Class WhoisProtectOrderPurchase
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 *
 * @property WhoisProtectOrderProduct $position
 */
class WhoisProtectOrderPurchase extends AbstractPremiumPurchase
{
    public $type = 'whois_protect_purchase';

    /** {@inheritdoc} */
    public static function operation()
    {
        return 'pay-feature';
    }

    /** {@inheritdoc} */
    public function renderNotes()
    {
        return Yii::t('hipanel.domain.premium', 'Your contact data privacy for domain {domain} is active till {date}', [
            'domain' => $this->position->name,
            'date' => '<b>' . Yii::$app->formatter->asDate($this->_result['expires']) . '</b>'
        ]);
    }
}
