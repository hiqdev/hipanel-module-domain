<?php

namespace hipanel\modules\domain\cart;

use Yii;

class PremiumPurchase extends AbstractPremiumPurchase
{
    /** {@inheritdoc} */
    public static function operation()
    {
        return 'Purchase';
    }

    /** {@inheritdoc} */
    public function renderNotes()
    {
        return Yii::t('hipanel:domain', 'Premium package is payed up to') . ' <b>' . Yii::$app->formatter->asDate($this->_result['expiration_date']) . '</b>';
    }
}
