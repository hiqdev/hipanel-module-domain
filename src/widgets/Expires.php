<?php
/**
 * @link    http://hiqdev.com/hipanel-module-domain
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\domain\widgets;

use hipanel\modules\domain\models\Domain;

class Expires extends \hipanel\widgets\Label
{
    /**
     * @var Domain
     */
    public $model;

    public function run () {
        $expires = $this->model->expires;
        if (strtotime("+30 days", time()) < strtotime($expires)) $class = 'none';
        elseif (strtotime("+0 days", time()) < strtotime($expires)) $class = 'warning';
        else $class = 'danger';

        $this->color = $class;
        $this->label = \Yii::$app->formatter->asDate($expires);
        parent::run();
    }

}
