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
        return Yii::t('hipanel.domain.premium', 'The premium package was activated till {date}', ['date' => '<b>' . Yii::$app->formatter->asDate($this->_result['expires']) . '</b>']);
    }
}
