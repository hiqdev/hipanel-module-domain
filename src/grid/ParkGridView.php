<?php

namespace hipanel\modules\domain\grid;

class ParkGridView extends AbstractPremiumGrid
{
    public function columns()
    {
        return array_merge(parent::columns(), []);
    }
}
