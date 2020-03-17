<?php

namespace hipanel\modules\domain\widgets;

use hipanel\helpers\ArrayHelper;
use hipanel\helpers\Url;
use hipanel\modules\domain\cart\WhoisProtectOrderProduct;
use hiqdev\themes\adminlte\CheckboxStyleAsset;
use hiqdev\yii2\cart\CartPositionInterface;
use hipanel\modules\finance\cart\Calculation;
use hiqdev\yii2\cart\RelatedPositionInterface;
use hiqdev\yii2\cart\ShoppingCart;
use yii\base\Widget;
use yii\helpers\Html;
use hipanel\modules\finance\logic\Calculator;
use Yii;
use yii\helpers\Json;
use yii\web\JsExpression;

class WithWhoisProtectPosition extends Widget
{
    /** @var ShoppingCart */
    public $cart;

    /** @var CartPositionInterface */
    public $mainPosition;

    /** @var CartPositionInterface */
    public $relatedPosition;

    public function init()
    {
        CheckboxStyleAsset::register($this->view);
    }

    public function run()
    {
        $currentPositions = $this->cart->getPositions();
        $calculationId = $this->relatedPosition->getCalculationModel()->calculation_id;
        $price = $this->cart->formatCurrency($this->relatedPosition->cost, $this->relatedPosition->currency);

        $checkboxId = mt_rand();
        $parentExists = ArrayHelper::getColumn($currentPositions, 'parent_id');
        $withWpCheckbox = Html::checkbox('with_whois_protect', !empty($parentExists[$calculationId]), [
            'id' => $checkboxId,
            'class' => 'option-input',
            'onClick' => new JsExpression(<<<'JS'
                document.querySelector('.invoice-overlay').style.display = 'block';
                if (this.checked === false) {
                    window.location.replace(encodeURI(this.dataset.fromcart));
                } else {
                    window.location.replace(encodeURI(this.dataset.tocart));
                }
JS
            ),
            'data' => [
                'tocart' => Url::toRoute([
                    '@domain/add-to-cart-whois-protect',
                    'name' => $this->mainPosition->name,
                    'parent_id' => $this->mainPosition->getCalculationModel()->calculation_id,
                ]),
                'fromcart' => Url::toRoute(['@cart/remove', 'id' => $calculationId]),
            ],
        ]);

        return Html::label(
            sprintf(
                '%s %s <br/>%s',
                $withWpCheckbox,
                Yii::t('hipanel:domain', 'Add WHOIS Protectection for {price}', compact('price')),
                Html::tag(
                    'p',
                    Yii::t('hipanel:domain', 'Using the Privacy Protection service, you may prevent such abuse. When you enable WHOIS privacy for your domain name, we replace your Contact Details in the WHOIS information with our company contact details, thus, masking your personal contact data.'),
                    ['class' => 'text-muted', 'style' => 'font-size: smaller; margin: .7em 0;']
                )
            ),
            $checkboxId,
            ['class' => 'with-wp', 'style' => 'margin-top: 1em']
        );
    }
}
