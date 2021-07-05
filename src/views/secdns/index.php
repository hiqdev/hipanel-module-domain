<?php

use hipanel\modules\domain\grid\SecdnsGridView;
use hipanel\widgets\IndexPage;
use yii\bootstrap\Html;

/**
 * @var $this \yii\web\View
 * @var $dataProvider \hiqdev\hiart\ActiveDataProvider
 */
$this->title = Yii::t('hipanel', 'Domains');
$this->params['subtitle'] = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $page = IndexPage::begin(compact('model', 'dataProvider')) ?>
    <?= $page->setSearchFormData() ?>

    <?php $page->beginContent('main-actions') ?>
        <?= Html::a(Yii::t('hipanel:domain', 'Create SecDNS record'), ['/domain/secdns/create'], [
            'class' => 'btn btn-sm btn-success',
            'data-ga-check' => true,
        ]) ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('sorter-actions') ?>
        <?= $page->renderSorter([
            'attributes' => [
                'domain', 'client', 'seller',
            ],
        ]) ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('bulk-actions') ?>
        <?= $page->renderBulkDeleteButton('delete') ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('table') ?>
        <?php $page->beginBulkForm() ?>
            <?= SecdnsGridView::widget([
                'boxed' => false,
                'dataProvider' => $dataProvider,
                'filterModel'  => $model,
                'columns' => $representationCollection->getByName($uiModel->representation)->getColumns(),
            ]) ?>
        <?php $page->endBulkForm() ?>
    <?php $page->endContent() ?>
<?php $page->end() ?>
