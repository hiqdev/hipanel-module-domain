<?php

namespace hipanel\modules\domain\grid;

use hipanel\grid\BoxedGridView;

class ParkGridView extends BoxedGridView
{
    public function columns()
    {
        return array_merge(parent::columns(), []);
    }
}
