<?php

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
        return Yii::t('hipanel:domain', 'Premium package is payed up to') . ' <b>' . Yii::$app->formatter->asDate($this->_result['expires']) . '</b>';
    }
}
