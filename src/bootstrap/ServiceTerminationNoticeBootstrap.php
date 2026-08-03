<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\bootstrap;

use hipanel\modules\client\models\Client;
use hipanel\modules\domain\widgets\ServiceTerminationNotice;
use yii\base\BootstrapInterface;
use yii\web\View;
use Yii;

class ServiceTerminationNoticeBootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        if (empty(Yii::$app->params['service-termination-notice.enabled'])) {
            return;
        }

        $user = Yii::$app->user;
        if ($user->isGuest || $user->identity->type !== Client::TYPE_CLIENT) {
            return;
        }

        if (!in_array($user->identity->seller, Yii::$app->params['service-termination-notice.sellers'], true)) {
            return;
        }

        Yii::$app->view->on(View::EVENT_END_BODY, function () {
            echo ServiceTerminationNotice::widget();
        });
    }
}
