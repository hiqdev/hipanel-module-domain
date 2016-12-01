<?php

namespace hipanel\modules\domain\widgets;

use kartik\base\Widget;
use yii\helpers\Html;

class CheckCircle extends Widget
{
    /**
     * @var boolean
     */
    public $value;

    public function run()
    {
        return Html::tag('i', null, ['class' => 'fa fa-fw fa-lg fa-check-circle pull-right', 'style' =>  'color: ' . (boolval($this->value) ? '#b3b3b3' : '#00a65a')]);
    }
}
