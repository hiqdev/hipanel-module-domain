<?php

namespace hipanel\modules\domain\grid;

class UrlfwGridView extends AbstractPremiumGrid
{
    public function columns()
    {
        return array_merge(parent::columns(), [
            'name' => [
                'value' => function ($model) {
                    return ltrim(join('.', [$model->name, $this->domain]), '.');
                },
            ],
        ]);
    }
}
