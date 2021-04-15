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
use yii\helpers\Url;

class GetPremiumButton extends Widget
{
    const PURCHASE = 'premium_dns_purchase';

    const RENEW = 'premium_dns_renew';

    public $model;

    public function run()
    {
        $this->getClientScript();
        if ($this->model->premium->is_active) {
            return Html::a(Yii::t('hipanel.domain.premium', 'Renew premium package for {price}'), [
                '@domain/add-to-cart-premium-renewal',
                'model_id' => Html::encode($this->model->id),
            ], [
                'class' => 'btn btn-success btn-xs fetch-premium-price',
                'data' => [
                    'pjax' => 0,
                ],
            ]);
        } else {
            return Html::a(Yii::t('hipanel.domain.premium', 'Buy premium package for {price}'), [
                '@domain/add-to-cart-premium',
                'name' => Html::encode($this->model->domain),
            ], [
                'class' => 'btn btn-success btn-xs fetch-premium-price',
                'data' => [
                    'pjax' => 0,
                ],
            ]);
        }
    }

    protected function buildUrl()
    {
        return Url::to([
            '@domain/get-premium-price',
            'id' => Html::encode($this->model->id),
            'client_id' => Html::encode($this->model->client_id),
            'type' => $this->model->is_premium ? self::RENEW : self::PURCHASE,
            'domain' => Html::encode($this->model->domain),
        ]);
    }

    protected function getClientScript()
    {
        $url = $this->buildUrl();
        $loader = '<i class="fa fa-refresh fa-spin fa-fw"></i>';
        $this->view->registerJs("
            var waitingForPrice = $('#premium').find('.fetch-premium-price');
            $.ajax({
                url: '{$url}',
                type : 'POST',
                beforeSend: function () {
                    waitingForPrice.each(function() {
                        var text = $(this).text();
                        $(this).data('original-text', text);
                        $(this).html(text.replace(/{price}/, '{$loader}'));
                    });
                },
                success: function(price) {
                    waitingForPrice.each(function() {
                        var oldText = $(this).data('original-text');
                        $(this).html(oldText.replace(/{price}/, price));
                    });
                }
            });
        ");
    }
}
