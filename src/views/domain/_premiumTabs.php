<?php

/** @var \hipanel\modules\domain\models\Domain $model */

use hipanel\modules\domain\grid\DomainGridView;
use hipanel\modules\domain\grid\MailfwGridView;
use hipanel\modules\domain\grid\ParkGridView;
use hipanel\modules\domain\grid\UrlfwGridView;
use hipanel\modules\domain\models\MailFw;
use hipanel\modules\domain\models\Park;
use hipanel\modules\domain\models\UrlFw;
use hipanel\modules\domain\widgets\UsePremiumFeaturesButton;
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

<div id="url-forwarding" class="tab-pane fade">
    <div class="panel panel-default">
        <div class="panel-heading premium-panels-heading">
            <h3 class="panel-title">
                <?= Yii::t('hipanel:domain', 'URL forwarding') ?>
            </h3>
        </div>
        <div class="panel-body">
            <?= $this->render('_formUrlfw', ['model' => new Urlfw, 'domain' => $model]) ?>
            <hr>
            <?= UrlfwGridView::widget([
                'dataProvider' => new ArrayDataProvider([
                    'allModels' => $model->urlfws,
                    'modelClass' => \hipanel\modules\domain\models\Urlfw::class,
                    'pagination' => ['pageSize' => count($model->mailfws)],
                ]),
                'emptyText' => Yii::t('hipanel:domain', 'URL forwarding is not configured'),
                'domain' => $model->domain,
                'is_premium' => $model->is_premium,
                'columns' => ['name', 'type_label', 'value', 'actions'],
            ]) ?>
        </div>
    </div>
</div>

<div id="email-forwarding" class="tab-pane fade">
    <div class="panel panel-default">
        <div class="panel-heading premium-panels-heading">
            <h3 class="panel-title">
                <?= Yii::t('hipanel:domain', 'Email forwarding') ?>
            </h3>
        </div>
        <div class="panel-body">
            <?= $this->render('_formMailfw', ['model' => new MailFw(), 'domain' => $model]) ?>
            <hr>
            <?= MailfwGridView::widget([
                'dataProvider' => new ArrayDataProvider([
                    'allModels' => $model->mailfws,
                    'modelClass' => Mailfw::class,
                    'pagination' => ['pageSize' => count($model->mailfws)],
                ]),
                'emptyText' => Yii::t('hipanel:domain', 'E-mail forwarding is not configured'),
                'domain' => $model->domain,
                'is_premium' => $model->is_premium,
                'columns' => ['name', 'value', 'actions'],
            ]) ?>
        </div>
    </div>
</div>

<div id="parking" class="tab-pane fade">
    <div class="panel panel-default">
        <div class="panel-heading premium-panels-heading">
            <h3 class="panel-title">
                <?= Yii::t('hipanel:domain', 'Parking') ?>
            </h3>
        </div>
        <div class="panel-body">
            <?= $this->render('_formPark', ['model' => new Park(), 'domain' => $model]) ?>
            <hr>
            <?= ParkGridView::widget([
                'dataProvider' => new ArrayDataProvider([
                    'allModels' => $model->park,
                    'modelClass' => Park::class,
                    'pagination' => ['pageSize' => count($model->mailfws)],
                ]),
                'emptyText' => Yii::t('hipanel:domain', 'Parking is not configured'),
                'domain' => $model->domain,
                'is_premium' => $model->is_premium,
                'columns' => ['title', 'siteheader', 'sitetext', 'actions'],
            ]) ?>
        </div>
    </div>
</div>


