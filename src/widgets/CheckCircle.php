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
        return Html::tag('i', null, ['class' => 'fa fa-fw fa-check-circle ' . (boolval($this->value) ? 'text-muted' : 'text-success')]);
    }
}
