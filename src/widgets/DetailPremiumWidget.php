<?php

namespace hipanel\modules\domain\widgets;

use yii\base\Widget;

class DetailPremiumWidget extends Widget
{
    public $model;

    public function run()
    {
        return 1;
    }

    protected function getGrid()
    {
        ForwardMailGridView::widget([
            'boxed' => false,
            'model' => $this->model,
            'columns' => [
                'premium_package',
                'url_redirect',
            ],
        ]);
    }
}
