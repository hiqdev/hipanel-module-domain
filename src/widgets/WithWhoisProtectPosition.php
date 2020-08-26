<?php

namespace hipanel\modules\domain\widgets;

use hipanel\helpers\ArrayHelper;
use hipanel\helpers\Url;
use hipanel\modules\domain\cart\WhoisProtectOrderProduct;
use hipanel\modules\domain\cart\WhoisProtectRenewalProduct;
use hipanel\assets\CheckboxStyleAsset;
use hiqdev\yii2\cart\CartPositionInterface;
use hiqdev\yii2\cart\ShoppingCart;
use yii\base\Widget;
use yii\helpers\Html;
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
        $this->view->registerJs('$(function () { $(\'[data-toggle="popover"]\').popover() });');
        $this->view->registerCss('.with-wp .popover-content { width: 35em; } .cart-finish .with-wp { display: none; }');
    }

    public function run(): string
    {
        $currentPositions = $this->cart->getPositions();
        $calculationId = $this->relatedPosition->getId();
        $price = $this->cart->formatCurrency($this->relatedPosition->getPrice(), $this->relatedPosition->currency);
        $parentExists = ArrayHelper::getColumn($currentPositions, 'parent_id');
        $isChecked = !empty($parentExists[$calculationId]);
        $operation = $this->getOperation();
        $checkboxId = mt_rand();
        $cartUrl = Json::htmlEncode(Url::toRoute('/cart/cart/index'));
        $withWpCheckbox = Html::checkbox('with_whois_protect', $isChecked, [
            'id' => $checkboxId,
            'class' => 'option-input',
            'onClick' => new JsExpression(<<<"JS"
                const action = (this.checked === false) ? encodeURI(this.dataset.fromcart) : encodeURI(this.dataset.tocart);
                const overlay = document.querySelector('.invoice-overlay');
                $.ajax({
                    url: action,
                    beforeSend: () => {
                      if (overlay) {
                        document.querySelector('.invoice-overlay').style.display = 'block';
                      }
                    },
                    success: () => {
                        $.ajax({
                            url: '' + $cartUrl,
                            success: cartHtml => {
                                if (document.querySelector('.content section.box')) {
                                    $('.content section.box').replaceWith(cartHtml);
                                } else if (document.querySelector('section.invoice')) {
                                    $('section.invoice').replaceWith(cartHtml);
                                }
                                hipanel.updateCart(() => {
                                    if (overlay) {
                                        document.querySelector('.invoice-overlay').style.display = 'none';
                                    }
                                });
                            },
                        });
                    }
                });
JS
            ),
            'data' => [
                'tocart' => $this->getToCartUrl(),
                'fromcart' => Url::toRoute(['@cart/remove', 'id' => $calculationId]),
            ],
        ]);
        $hintMessage = Yii::t('hipanel:domain', 'Using the Privacy Protection service, you may prevent such abuse. When you enable WHOIS privacy for your domain name, we replace your Contact Details in the WHOIS information with our company contact details, thus, masking your personal contact data.');

        return Html::label(
            sprintf(
                '%s %s',
                $withWpCheckbox,
                Html::tag('abbr', Yii::t('hipanel:domain', '{operation} WHOIS Protection for {price} per year', compact('price', 'operation')), [
                    'data' => [
                        'toggle' => 'popover',
                        'content' => $hintMessage,
                        'trigger' => 'hover',
                    ],
                ])
            ),
            $checkboxId,
            ['class' => 'with-wp', 'style' => 'margin-top: 1em']
        );
    }

    public function getToCartUrl(): string
    {
        return Url::toRoute([
            $this->view->theme->name === 'admintlte' ? '@domain/add-to-cart-whois-protect' : '/site/add-to-cart-whois-protect',
            'name' => $this->mainPosition->name,
            'parent_id' => $this->mainPosition->getId(),
            'quantity' => $this->relatedPosition->getQuantity(),
        ]);
    }

    private function getOperation(): string
    {
        if ($this->relatedPosition instanceof WhoisProtectOrderProduct) {
            return Yii::t('hipanel:domain', 'Add');
        }
        if ($this->relatedPosition instanceof WhoisProtectRenewalProduct) {
            return Yii::t('hipanel:domain', 'Renew');
        }

        return Yii::t('hipanel:domain', 'Unknown operation type');
    }
}
