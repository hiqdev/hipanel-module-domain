<?php

namespace hipanel\modules\domain\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class BuyPremiumButton extends Widget
{
    const RENEW = 'premium_dns_renew';

    const PURCHASE = 'premium_dns_purchase';

    public $model;

    public function run()
    {
        if ($this->model->is_premium) {
            return Html::a(Yii::t('hipanel:domain', 'Renew premium package'), '#', ['class' => 'btn btn-success btn-xs']);
        } else {
            $this->getClientScript();

            return Html::a(Yii::t('hipanel:domain', 'Active premium package only for '), '#', ['class' => 'btn btn-success btn-xs fetch-premium-price']);
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
        $this->view->registerJs("
            $('a[href=\"#premium\"][data-toggle=\"tab\"]').one('shown.bs.tab', function (e) {
                $.ajax({
                    url: '{$url}',
                    type : 'POST',
                    success: function(price) {
                        $('#premium').find('.fetch-premium-price').each(function() {
                            var old = $(this).text(); 
                            $(this).html(old + ' ' + price);
                        });
                    }
                });
            })
        ");
    }
}
