<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\widgets;

use hipanel\modules\domain\models\Domain;
use hipanel\widgets\PincodePrompt;

class AuthCode extends \yii\base\Widget
{
    /**
     * @var Domain
     */
    public $model;

    /**
     * @var boolean whether to ask pincode
     */
    public $askPincode;

    public function run()
    {
        $this->registerClientScript();

        return $this->render('auth-code', [
            'model' => $this->model,
            'view' => $this->getView(),
            'askPincode' => $this->askPincode,
        ]);
    }

    protected function registerClientScript()
    {
        echo PincodePrompt::widget();
    }
}
