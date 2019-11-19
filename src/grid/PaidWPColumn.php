<?php

namespace hipanel\modules\domain\grid;

use hipanel\modules\domain\models\Domain;
use hiqdev\bootstrap_switch\LabeledAjaxSwitch;
use hiqdev\higrid\DataColumn;
use Yii;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;

class PaidWPColumn extends DataColumn
{
    public $filter = false;

    public $contentOptions = ['class' => 'text-left', 'style' => 'vertical-align: middle;'];

    public $enableSorting = false;

    public $encodeLabel = false;

    public $popoverOptions = [
        'placement' => 'bottom',
        'selector' => 'span',
    ];

    public $format = 'raw';

    public function init(): void
    {
        parent::init();
        if (!$this->label) {
            $this->label = Html::tag('span', Yii::t('hipanel:domain', 'WHOIS protect'));
        }
        if (!$this->popover) {
            $this->popover = Yii::t('hipanel:domain', 'WHOIS protection');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function renderDataCellContent($model, $key, $index): string
    {
        /** @var Domain $model */
        $needToPayOptions = [];
        if ($model->needToPayWhoisProtect()) {
            $modalId = 'add-to-cart-whois-protect-modal-' . $key;
            Yii::$app->view->on(View::EVENT_END_BODY, static function () use ($model, $modalId) {
                Modal::begin([
                    'id' => $modalId,
                    'header' => Html::tag('h4', Yii::t('hipanel:domain', 'Paid WHOIS protect'), ['class' => 'modal-title']),
                    'size' => Modal::SIZE_SMALL,
                    'toggleButton' => false,
                ]);
                echo Html::tag(
                    'p',
                    Yii::t('hipanel:domain', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.'),
                    ['style' => 'margin-bottom: 2em;', 'class' => 'text-justify']
                );
                echo Html::a(
                    Yii::t('hipanel:domain', 'Add to cart'),
                    ['@domain/add-to-cart-whois-protect', 'name' => $model->domain],
                    ['class' => 'btn btn-block btn-success']
                );
                Modal::end();
            });
            $needToPayOptions = [
                'url' => '#',
                'pluginOptions' => [
                    'onSwitchChange' => new JsExpression(" evt => {
                    evt.stopPropagation();
                    $('#{$modalId}').modal('show');
                    
                    return false;
                }"),
                ],
            ];
        }

        return LabeledAjaxSwitch::widget(ArrayHelper::merge([
            'model' => $model,
            'attribute' => 'whois_protected',
            'url' => Url::toRoute('set-whois-protect'),
            'pluginOptions' => [
                'offColor' => 'warning',
                'readonly' => !$model->isWPChangeable(),
            ],
            'labels' => [
                0 => [
                    'style' => 'display: none;',
                    'class' => 'text-danger md-pl-10',
                    'content' => Yii::t('hipanel:domain', 'The contact data is visible to everybody in the Internet'),
                ],
                1 => [
                    'style' => 'display: none;',
                    'class' => 'small text-muted font-normal md-pl-10',
                    'content' => Yii::t('hipanel:domain', 'The contact data is protected and not exposed'),
                ],
            ],
        ], $needToPayOptions));
    }
}
