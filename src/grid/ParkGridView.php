<?php

namespace hipanel\modules\domain\grid;

use hipanel\grid\GridView;

class ParkGridView extends GridView
{
    public $domain;

    public $layout = "<div class=\"table-responsive\">{items}</div>";

    public $tableOptions = ['class' => 'table'];

    public function columns()
    {
        return array_merge(parent::columns(), []);
    }
}
