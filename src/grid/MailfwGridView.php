<?php

namespace hipanel\modules\domain\grid;

class MailfwGridView extends AbstractPremiumGrid
{
    public function columns()
    {
        return array_merge(parent::columns(), [
            'name' => [
                'value' => function ($model) {
                    return $model->name . '@' . $this->domain;
                },
            ],
        ]);
    }
}
