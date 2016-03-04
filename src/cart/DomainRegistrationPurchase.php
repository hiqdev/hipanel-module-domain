<?php

/*
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\cart;

use Yii;

class DomainRegistrationPurchase extends AbstractDomainPurchase
{
    /** {@inheritdoc} */
    public static function operation()
    {
        return 'Register';
    }

    /** {@inheritdoc} */
    public function renderNotes()
    {
        return Yii::t('hipanel/domain', 'Domain is payed up to') . ' <b>' . Yii::$app->formatter->asDate($this->_result['expiration_date']) . '</b>';
    }
}
