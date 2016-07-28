<?php

/*
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\widgets;

use Yii;

class AuthCode extends \yii\base\Widget
{
    public $domainId;

    public function run()
    {
        $errorText = Yii::t('hipanel', 'An error occurred. Try again please.');

        return $this->render('authcode_view', [
            'domainId' => $this->domainId,
            'view' => $this->getView(),
        ]);
    }
}
