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

class PremiumOrderPurchase extends AbstractPremiumPurchase
{
    public $type = 'premium_dns_purchase';

    /** {@inheritdoc} */
    public static function operation()
    {
        return 'pay-feature';
    }

    /** {@inheritdoc} */
    public function renderNotes()
    {
        return Yii::t('hipanel.domain.premium', 'The premium package was activated till {date}', ['date' => '<b>' . Yii::$app->formatter->asDate($this->_result['expires']) . '</b>']);
    }
}
