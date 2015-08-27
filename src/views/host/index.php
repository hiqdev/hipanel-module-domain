<?php

use hipanel\modules\domain\grid\HostGridView;
use hipanel\widgets\ActionBox;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title    = Yii::t('app', 'Name Servers');
$this->subtitle = Yii::t('app', array_filter(Yii::$app->request->get($model->formName(), [])) ? 'filtered list' : 'full list');
$this->breadcrumbs[] = $this->title;

?>

<?php $box = ActionBox::begin(['model' => $model, 'dataProvider' => $dataProvider]) ?>
    <?php $box->beginActions() ?>
        <?= $box->renderCreateButton(Yii::t('app', 'Create name server')) ?>
        <?= $box->renderSearchButton() ?>
        <?= $box->renderSorter([
            'attributes' => [
                'host',
                'domain',
                'ip',
                'client',
                'seller'
            ],
        ]) ?>
        <?= $box->renderPerPage() ?>
    <?php $box->endActions() ?>
    <?php $box->beginBulkActions() ?>
        <?= $box->renderBulkButton(Yii::t('app', 'Change IP'), 'update') ?>
        <?= $box->renderDeleteButton() ?>
    <?php $box->endBulkActions() ?>
    <?= $box->renderSearchForm() ?>
<?php $box->end() ?>

<?php $box->beginBulkForm() ?>
    <?= HostGridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $model,
        'columns'      => [
            'checkbox',
            'host', 'ips', 'domain',
            'client_id', 'seller_id',
        ],
    ]) ?>
<?php $box->endBulkForm() ?>
