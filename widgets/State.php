<?php
/**
 * @link    http://hiqdev.com/hipanel-module-domain
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\domain\widgets;

use hipanel\base\Re;

class State extends \hipanel\widgets\Label
{
    public $model = [];

    public function run () {
        $state = $this->model->state;
        if ($state=='ok') $class = 'info';
        elseif ($state=='blocked' || $state=='expired') $class = 'danger';
        else $class = 'warning';

        $this->zclass   = $class;
        $this->label    = Re::l($this->model->state_label);
        parent::run();
    }

}
