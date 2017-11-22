<?php

/** @var \hipanel\modules\domain\models\Domain $model */

use hipanel\modules\domain\grid\DomainGridView;
use hipanel\modules\domain\grid\MailfwGridView;
use hipanel\modules\domain\grid\ParkGridView;
use hipanel\modules\domain\grid\UrlfwGridView;
use yii\bootstrap\Html;
use yii\data\ArrayDataProvider;

$this->registerCss('
.premium-panels-heading {  
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    flex-wrap: wrap;
}
.premium-panels-heading .panel-title {
    line-height: 25px;
}
');

?>

<div id="premium" class="tab-pane fade">
    <?= DomainGridView::detailView([
        'boxed' => false,
        'model' => $model,
        'columns' => [
            'is_premium',
            'premium_autorenewal',
        ],
    ]) ?>
</div>

<div id="email-forwarding" class="tab-pane fade">
    <div class="panel panel-default">
        <div class="panel-heading premium-panels-heading">
            <h3 class="panel-title">
                <?= Yii::t('hipanel:domain', 'URL forwarding') ?>
            </h3>
            <?= Html::a(Yii::t('hipanel', 'Change'), '#', ['class' => 'btn btn-primary btn-sm']) ?>
        </div>
        <div class="panel-body">
            <?= UrlfwGridView::widget([
                'dataProvider' => new ArrayDataProvider([
                    'allModels' => $model->urlfws,
                    'modelClass' => \hipanel\modules\domain\models\Urlfw::class,
                    'pagination' => ['pageSize' => count($model->mailfws)],
                ]),
                'domain' => $model->domain,
                'columns' => ['name', 'type_label', 'value'],
            ]); ?>
        </div>
    </div>
</div>

<div id="url-forwarding" class="tab-pane fade">
    <div class="panel panel-default">
        <div class="panel-heading premium-panels-heading">
            <h3 class="panel-title">
                <?= Yii::t('hipanel:domain', 'Email forwarding') ?>
            </h3>
            <?= Html::a(Yii::t('hipanel', 'Change'), '#', ['class' => 'btn btn-primary btn-sm']) ?>
        </div>
        <div class="panel-body">
            <?= MailfwGridView::widget([
                'dataProvider' => new ArrayDataProvider([
                    'allModels' => $model->mailfws,
                    'modelClass' => \hipanel\modules\domain\models\Mailfw::class,
                    'pagination' => ['pageSize' => count($model->mailfws)],
                ]),
                'domain' => $model->domain,
                'columns' => ['name', 'value'],
            ]); ?>
        </div>
    </div>
</div>

<div id="parking" class="tab-pane fade">
    <div class="panel panel-default">
        <div class="panel-heading premium-panels-heading">
            <h3 class="panel-title">
                <?= Yii::t('hipanel:domain', 'Parking') ?>
            </h3>
            <?= Html::a(Yii::t('hipanel', 'Change'), '#premium', [
                'data-toggle' => 'tab',
                'class' => 'btn btn-primary btn-sm',
            ]) ?>
        </div>
        <div class="panel-body">
            <?= ParkGridView::widget([
                'dataProvider' => new ArrayDataProvider([
                    'allModels' => $model->parks,
                    'modelClass' => \hipanel\modules\domain\models\Park::class,
                    'pagination' => ['pageSize' => count($model->mailfws)],
                ]),
                'emptyText' => Yii::t('hipanel:domain', 'Parking is not configured'),
                'domain' => $model->domain,
                'columns' => ['title'],
            ]); ?>
        </div>
    </div>
</div>


