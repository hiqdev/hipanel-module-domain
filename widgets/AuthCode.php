<?php

namespace hipanel\modules\domain\widgets;

use Yii;

class AuthCode extends \yii\base\Widget
{
    public $domainId;

    public function run()
    {
        $errorText = Yii::t('app', 'An error has occurred. Try again please.');

        return $this->render('authcode_view', [
            'domainId' => $this->domainId,
            'view' => $this->getView(),
        ]);
    }
}

