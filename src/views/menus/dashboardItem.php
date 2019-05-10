<?php

use hipanel\modules\dashboard\widgets\ObjectsCountWidget;
use hipanel\modules\dashboard\widgets\SearchForm;
use hipanel\modules\dashboard\widgets\SmallBox;
use hipanel\modules\domain\models\DomainSearch;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
    <?php $box = SmallBox::begin([
        'boxTitle' => Yii::t('hipanel', 'Domains'),
    ]) ?>
        <?php $box->beginBody() ?>
            <?= ObjectsCountWidget::widget([
                'totalCount' => $totalCount['domains'],
                'ownCount' => $model->count['domains'],
            ]) ?>
            <br>
            <br>
            <?= SearchForm::widget([
                'formOptions' => [
                    'id' => 'domain-search',
                    'action' => Url::to('@domain/index'),
                ],
                'model' => new DomainSearch(),
                'attribute' => 'domain_like',
                'buttonColor' => SmallBox::COLOR_AQUA,
            ]) ?>
            <div class="clearfix"></div>
        <?php $box->endBody() ?>
        <?php $box->beginFooter() ?>
            <?= Html::a(Yii::t('hipanel', 'View') . $box->icon(), '@domain/index', ['class' => 'small-box-footer']) ?>
            <?php if ($model->count['contacts']) : ?>
                <?= Html::a(Yii::t('hipanel', 'Contacts') . ': ' . $model->count['contacts'] . $box->icon(), '@contact/index', ['class' => 'small-box-footer']) ?>
            <?php endif ?>
            <?php if (Yii::$app->user->can('deposit')) : ?>
                <?= Html::a(Yii::t('hipanel', 'Buy') . $box->icon('fa-shopping-cart'), '/domain/check', ['class' => 'small-box-footer']) ?>
            <?php endif ?>
        <?php $box->endFooter() ?>
    <?php $box::end() ?>
</div>
