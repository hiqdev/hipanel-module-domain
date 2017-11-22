<?php

namespace hipanel\modules\domain\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class BuyPremiumButton extends Widget
{
    const PURCHASE = 'premium_dns_purchase';

    const RENEW = 'premium_dns_renew';

    public $model;

    public function run()
    {
        $this->getClientScript();
        if ($this->model->is_premium) {
            return Html::a(Yii::t('hipanel:domain', 'Renew premium package for '), [
                '@domain/add-to-cart-premium-renewal',
                'name' => $this->model->domain,
            ], [
                'class' => 'btn btn-success btn-xs fetch-premium-price',
                'data' => [
                    'pjax' => 0,
                ],
            ]);
        } else {
            return Html::a(Yii::t('hipanel:domain', 'Buy premium package for '), [
                '@domain/add-to-cart-premium',
                'name' => $this->model->domain,
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
            'id' => $this->model->id,
            'type' => $this->model->is_premium ? self::RENEW : self::PURCHASE,
            'domain' => $this->model->domain,
        ]);
    }

    protected function getClientScript()
    {
        $url = $this->buildUrl();
        $loader = '<i class="fa fa-refresh fa-spin fa-fw"></i>';
        $this->view->registerJs("
            $('a[href=\"#premium\"][data-toggle=\"tab\"]').one('shown.bs.tab', function (e) {
                var waitingForPrice = $('#premium').find('.fetch-premium-price');
                $.ajax({
                    url: '{$url}',
                    type : 'POST',
                    beforeSend: function () {
                        waitingForPrice.each(function() {
                            $(this).append('{$loader}');
                        });
                    },
                    success: function(price) {
                        waitingForPrice.each(function() {
                            var oldText = $(this).text(); 
                            $(this).html(oldText + ' ' + price);
                        });
                    }
                });
            })
        ");
    }
}
