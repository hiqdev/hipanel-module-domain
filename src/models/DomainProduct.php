<?php

namespace hipanel\modules\domain\models;

use hipanel\models\CartPosition;

class DomainProduct extends CartPosition
{
    /**
     * @var Domain
     */
    public $model;

    public $icon = '<i class="fa fa-globe"></i>'; //'<i class="fa fa-server text-muted"></i>';
}