<?php

namespace hipanel\modules\domain\grid;

use hiqdev\higrid\GridView;

class MailfwGridView extends GridView
{
    public $domain;

    public $layout = "<div class=\"table-responsive\">{items}</div>";

    public $tableOptions = ['class' => 'table'];

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
