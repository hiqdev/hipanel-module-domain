<?php

namespace hipanel\modules\domain\widgets;

use hipanel\helpers\ArrayHelper;
use hipanel\helpers\Url;
use hipanel\modules\domain\cart\WhoisProtectOrderProduct;
use hiqdev\themes\adminlte\CheckboxStyleAsset;
use hiqdev\yii2\cart\CartPositionInterface;
use hipanel\modules\finance\cart\Calculation;
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
    public $cartPosition;

    public function init()
    {
        CheckboxStyleAsset::register($this->view);
    }

    public function run()
    {
        $currentPositions = $this->cart->getPositions();

        $wpPostion = new WhoisProtectOrderProduct(['name' => $this->cartPosition->name]);
        $wpPostion->setQuantity($this->cartPosition->getQuantity());
        $calculator = new Calculator([$wpPostion]);
        $calculationId = $wpPostion->getCalculationModel()->calculation_id;
        $calculation = $calculator->getCalculation($calculationId);
        $value = $calculation->forCurrency(Yii::$app->params['currency']);

        $wpPostion->setPrice($value->price);
        $wpPostion->setValue($value->value);
        $wpPostion->setCurrency($value->currency);

        $price = $this->cart->formatCurrency($wpPostion->cost, $wpPostion->currency);

        $checkboxId = mt_rand();
        $parentExists = ArrayHelper::getColumn($currentPositions, 'parent_id');
        $tocart =
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
                    'name' => $this->cartPosition->name,
                    'parent_id' => $this->cartPosition->getCalculationModel()->calculation_id,
                ]),
                'fromcart' => Url::toRoute(['@cart/remove', 'id' => $calculationId]),
            ],
        ]);

        return Html::label(
            sprintf(
                '%s %s <br/>%s',
                $withWpCheckbox,
                Yii::t('hipanel:domain', 'Add Whois Protectection for {price}', compact('price')),
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
