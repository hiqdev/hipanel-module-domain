<?php

namespace hipanel\modules\domain\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class PremiumAlert extends Widget
{
    public function run()
    {
        $html = '';
        $html .= Html::beginTag('blockquote', ['class' => 'text-warning', 'style' => 'border-left-color: #e08e0b']);
        $html .= Html::tag('p', Yii::t('hipanel.domain.premium', 'You need to activate the premium package to change the settings'));
        $html .= Html::beginTag('p');
        $html .= Html::a(Yii::t('hipanel.domain.premium', 'Active premium package'), '#premium', [
            'class' => 'btn btn-warning',
            'data' => [
                'toggle' => 'tab',
                'dismiss' => 'modal',
            ],
        ]);
        $html .= Html::endTag('p');
        $html .= Html::endTag('blockquote');

        return $html;
    }
}
