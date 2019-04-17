<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\web\View;

class PremiumAlert extends Widget
{
    public function run()
    {
        $this->registerClientScript();
        $html = '';
        $html .= Html::beginTag('blockquote', ['class' => 'text-warning', 'style' => 'border-left-color: #e08e0b']);
        $html .= Html::tag('p', Yii::t('hipanel.domain.premium', 'You need to activate the premium package to change the settings'));
        $html .= Html::beginTag('p');
        $html .= Html::a(Yii::t('hipanel.domain.premium', 'Activate premium package'), '#', [
            'class' => 'btn btn-warning go-to-premium-tab',
        ]);
        $html .= Html::endTag('p');
        $html .= Html::endTag('blockquote');

        return $html;
    }

    private function registerClientScript(): void
    {
        $this->view->registerJs('
            $(".go-to-premium-tab").on("click", function (e) { e.preventDefault(); $("a[href=\'#premium\']").click(); });
        ', View::POS_READY, 'premium-tab-click');
    }
}
