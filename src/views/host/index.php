<?php

use hipanel\modules\domain\grid\HostGridView;
use hipanel\widgets\ActionBox;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title    = Yii::t('app', 'Name Servers');
$this->subtitle = Yii::t('app', Yii::$app->request->queryParams ? 'filtered list' : 'full list');
$this->breadcrumbs[] = $this->title;

?>

<?php $box = ActionBox::begin(['model' => $model, 'options' => ['class' => 'box-info']]) ?>
    <?php $box->beginActions() ?>
        <?= $box->renderCreateButton(Yii::t('app', 'Create {modelClass}', ['modelClass' => Yii::t('app', 'Name server')])) ?>
        &nbsp;
        <?= $box->renderSearchButton() ?>
    <?php $box->endActions() ?>
    <?php $box->beginBulkActions() ?>
        <?= $box->renderBulkButton(Yii::t('app', 'Change IP'), Url::to('update')) ?>
        <?= $box->renderBulkButton(Yii::t('app', 'Delete'), Url::to('delete')) ?>
    <?php $box->endBulkActions() ?>
    <?= $box->renderSearchForm() ?>
<?php $box::end() ?>

<?php $box->beginBulkForm() ?>
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

<?php $box::endBulkForm() ?>
