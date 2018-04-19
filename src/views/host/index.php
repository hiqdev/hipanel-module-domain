<?php

use hipanel\modules\domain\grid\HostGridView;
use hipanel\widgets\AjaxModal;
use hipanel\widgets\IndexPage;
use hipanel\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Html;

$this->title    = Yii::t('hipanel:domain', 'Name Servers');
$this->params['subtitle'] = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
$this->params['breadcrumbs'][] = $this->title;

?>

<?php Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true])) ?>
    <?php $page = IndexPage::begin(compact('model', 'dataProvider')) ?>
    <?php $page->beginContent('main-actions') ?>
        <?= Html::a(Yii::t('hipanel:domain', 'Create name server'), 'create', ['class' => 'btn btn-sm btn-success']) ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('sorter-actions') ?>
        <?= $page->renderSorter([
            'attributes' => [
                'host', 'domain', 'ip',
                'client', 'seller',
            ],
        ]) ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('bulk-actions') ?>
        <?= AjaxModal::widget([
            'id' => 'bulk-set-ips-modal',
            'bulkPage' => true,
            'header' => Html::tag('h4', Yii::t('hipanel:domain', 'Set IPs'), ['class' => 'modal-title']),
            'scenario' => 'bulk-set-ips',
            'actionUrl' => ['bulk-set-ips'],
            'size' => Modal::SIZE_LARGE,
            'toggleButton' => ['label' => Yii::t('hipanel:domain', 'Set IPs'), 'class' => 'btn btn-sm btn-default'],
        ]) ?>
        <?= $page->renderBulkButton(Yii::t('hipanel', 'Delete'), 'delete', 'danger')?>
    <?php $page->endContent('bulk-actions') ?>

    <?php $page->beginContent('table') ?>
        <?php $page->beginBulkForm() ?>
            <?= HostGridView::widget([
                'boxed' => false,
                'dataProvider' => $dataProvider,
                'filterModel'  => $model,
                'columns'      => [
                    'checkbox',
                    'host', 'ips', 'domain',
                    'client_id', 'seller_id',
                ],
            ]) ?>
        <?php $page->endBulkForm() ?>
    <?php $page->endContent() ?>
    <?php $page->end() ?>
<?php Pjax::end() ?>
