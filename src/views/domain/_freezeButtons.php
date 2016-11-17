<?php

use hipanel\widgets\ModalButton;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<?php if (!$model->isFreezed() && Yii::$app->user->can('domain.freeze')) : ?>
    <li>
        <?= ModalButton::widget([
            'model' => $model,
            'scenario' => 'enable-freeze',
            'button' => [
                'label' => '<i class="fa fa-fw fa-lock"></i>' . Yii::t('hipanel:domain', 'Freeze domain'),
            ],
            'modal' => [
                'header' => Html::tag('h4', Yii::t('hipanel:domain', 'Confirm domain freezing')),
                'headerOptions' => ['class' => 'label-info'],
                'footer' => [
                    'label' => Yii::t('hipanel:domain', 'Freeze domain'),
                    'data-loading-text' => Yii::t('hipanel:domain', 'Freezing domain...'),
                    'class' => 'btn btn-danger',
                ],
            ],
            'body' => Yii::t('hipanel:domain',
                'Are you sure, that you want to freeze domain <b>{domain}</b>?',
                ['domain' => $model->domain]
            )
        ]) ?>
    </li>
<?php endif ?>
<?php if ($model->isFreezed() && Yii::$app->user->can('domain.unfreeze')) : ?>
    <li>
        <?= ModalButton::widget([
            'model' => $model,
            'scenario' => 'disable-freeze',
            'button' => [
                'label' => '<i class="fa fa-fw fa-unlock"></i>' . Yii::t('hipanel:domain', 'Unfreeze domain'),
            ],
            'modal' => [
                'header' => Html::tag('h4', Yii::t('hipanel:domain', 'Confirm domain unfreezing')),
                'headerOptions' => ['class' => 'label-info'],
                'footer' => [
                    'label' => Yii::t('hipanel:domain', 'Unfreeze domain'),
                    'data-loading-text' => Yii::t('hipanel:domain', 'Unfreezing domain...'),
                    'class' => 'btn btn-danger',
                ],
            ],
            'body' => Yii::t('hipanel:domain',
                'Are you sure, that you want to unfreeze domain <b>{domain}</b>?',
                ['domain' => $model->domain]
            )
        ]) ?>
    </li>
<?php endif ?>
<?php if (!$model->isWPFreezed() && Yii::$app->user->can('domain.freeze')) : ?>
    <li>
        <?= ModalButton::widget([
            'model' => $model,
            'scenario' => 'enable-freeze-w-p',
            'button' => [
                'label' => '<i class="fa fa-fw fa-lock"></i>' . Yii::t('hipanel:domain', 'Freeze WHOIS-protect'),
            ],
            'modal' => [
                'header' => Html::tag('h4', Yii::t('hipanel:domain', 'Confirm freezing WHOIS-protect')),
                'headerOptions' => ['class' => 'label-info'],
                'footer' => [
                    'label' => Yii::t('hipanel:domain', 'Freeze WHOIS-protect'),
                    'data-loading-text' => Yii::t('hipanel:domain', 'Freezing WHOIS-protect...'),
                    'class' => 'btn btn-danger',
                ],
            ],
            'body' => Yii::t('hipanel:domain',
                'Are you sure, that you want to freeze WHOIS-protect <b>{domain}</b>?',
                ['domain' => $model->domain]
            )
        ]) ?>
    </li>
<?php endif ?>
<?php if ($model->isWPFreezed() && Yii::$app->user->can('domain.unfreeze')) : ?>
    <li>
        <?= ModalButton::widget([
            'model' => $model,
            'scenario' => 'disable-freeze-w-p',
            'button' => [
                'label' => '<i class="fa fa-fw fa-unlock"></i>' . Yii::t('hipanel:domain', 'Unfreeze WHOIS-protect'),
            ],
            'modal' => [
                'header' => Html::tag('h4', Yii::t('hipanel:domain', 'Confirm unfreezing WHOIS-protect')),
                'headerOptions' => ['class' => 'label-info'],
                'footer' => [
                    'label' => Yii::t('hipanel:domain', 'Unfreeze WHOIS-protect'),
                    'data-loading-text' => Yii::t('hipanel:domain', 'Unfreezing WHOIS-protect...'),
                    'class' => 'btn btn-danger',
                ],
            ],
            'body' => Yii::t('hipanel:domain',
                'Are you sure, that you want to unfreeze WHOIS-protect for domain <b>{domain}</b>?',
                ['domain' => $model->domain]
            )
        ]) ?>
    </li>
<?php endif ?>
