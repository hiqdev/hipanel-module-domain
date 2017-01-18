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

class AuthCode extends \yii\base\Widget
{
    public $model;

    public function run()
    {
        return $this->render('AuthCode', [
            'model' => $this->model,
            'view' => $this->getView(),
        ]);
    }
}
