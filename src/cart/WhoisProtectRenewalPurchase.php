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
use yii\helpers\Html;

class WhoisProtectRenewalPurchase extends AbstractPremiumPurchase
{
    public $type = 'whois_protect_renew';

    /** {@inheritdoc} */
    public static function operation()
    {
        return 'pay-feature';
    }

    /** {@inheritdoc} */
    public function renderNotes()
    {
        return Yii::t('hipanel.domain.premium', 'Your contact data privacy for domain {domain} is active till {date}', [
            'domain' => Html::tag('strong', $this->position->name),
            'date' => Html::tag('strong', Yii::$app->formatter->asDate($this->_result['expires'])),
        ]);
    }
}
