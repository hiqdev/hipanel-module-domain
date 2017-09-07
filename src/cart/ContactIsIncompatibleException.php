<?php

namespace hipanel\modules\domain\cart;

use Yii;
use yii\helpers\Url;

class ContactIsIncompatibleException extends \hipanel\modules\finance\cart\NotPurchasablePositionException
{
    protected $requestPassport = false;

    public static function passportRequired()
    {
        $exception = new self();
        $exception->requestPassport = true;

        return $exception;
    }

    public static function generalDataRequired()
    {
        return new self();
    }

    public function resolve()
    {
        Yii::$app->response->redirect(Url::to(['@domain-contact/request', 'requestPassport' => $this->requestPassport]));
        Yii::$app->end();
    }
}
