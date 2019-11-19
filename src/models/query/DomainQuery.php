<?php

namespace hipanel\modules\domain\models\query;

use hiqdev\hiart\ActiveQuery;
use Yii;

class DomainQuery extends ActiveQuery
{
    public function withPaidWhoisProtect(): self
    {
        if (Yii::$app->getModule('domain')->whoisProtectPaid) {
            $this->addselect(['with_paidwp']);
            $this->joinwith('paidwp');
        }

        return $this;
    }
}
