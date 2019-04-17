<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\widgets;

use hipanel\modules\domain\models\Domain;

class Expires extends \hipanel\widgets\Label
{
    /**
     * @var Domain
     */
    public $model;

    public function init()
    {
        $expires = $this->model->expires;
        if ($expires === null) {
            $this->color = 'none';
        } elseif (strtotime('+30 days', time()) < strtotime($expires)) {
            $this->color = 'none';
        } elseif (strtotime('+0 days', time()) < strtotime($expires)) {
            $this->color = 'warning';
        } else {
            $this->color = 'danger';
        }

        $this->addClass = 'text-nowrap';
        $this->label = \Yii::$app->formatter->asDate($expires);
        parent::init();
    }
}
