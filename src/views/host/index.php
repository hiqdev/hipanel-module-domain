<?php

use hipanel\modules\domain\grid\HostGridView;
use hipanel\widgets\ActionBox;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title    = Yii::t('app', 'Name Servers');
$this->subtitle = Yii::t('app', Yii::$app->request->queryParams ? 'filtered list' : 'full list');
$this->breadcrumbs[] = $this->title;

?>
<?= Html::beginForm() ?>

<?php $box = ActionBox::begin(['model' => $model, 'bulk' => true, 'options' => ['class' => 'box-info']]) ?>
    <?php $box->beginActions() ?>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', ['modelClass' => Yii::t('app', 'Name server')]), ['create'], ['class' => 'btn btn-primary']) ?>
        &nbsp;
        <?= $box->renderSearchButton() ?>
    <?php $box->endActions() ?>
    <?php $box->beginBulkActions() ?>
        <?= $box->renderBulkButton(Yii::t('app', 'Change IP'), Url::to('update')) ?>
        <?= $box->renderBulkButton(Yii::t('app', 'Delete'), Url::to('delete')) ?>
    <?php $box->endBulkActions() ?>
    <?= $box->renderSearchForm() ?>
<?php $box::end() ?>

<?= HostGridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns'      => [
        'checkbox',
        'host',
        'ips',
        'domain',
        'client_id',
        'seller_id',

    ],
]) ?>

<?= Html::endForm() ?>
