<?php

namespace hipanel\modules\domain\grid;

use hipanel\grid\ActionColumn;
use Yii;
use yii\helpers\Html;

class PremiumActionColumn extends ActionColumn
{
    public function init()
    {
        $this->template = '{update} {delete}';
        $this->visibleButtonsCount = 2;
        $this->options = [
            'style' => 'width: 15%',
        ];
        $this->buttons = [
            'update' => function ($url, $model, $key) {
                $html = Html::button('<i class="fa fa-pencil"></i> ' . Yii::t('hipanel', 'Update'), [
                    'class' => 'btn btn-default btn-xs edit-dns-toggle',
                ]);

                return $html;

            },
            'delete' => function ($url, $model, $key) {
                return 'delete';
            },
        ];
    }
}
