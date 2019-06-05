<?php

/**
 * @var string $domain
 * @var array $availableZone
 * @var \hipanel\modules\domain\models\Zone $model
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

use hipanel\modules\domain\grid\ZoneGridView;
use hipanel\widgets\IndexPage;
use hipanel\widgets\Pjax;
use yii\bootstrap\Html;

$this->title = Yii::t('hipanel:domain', 'Zone');
$this->params['subtitle'] = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
$this->params['breadcrumbs'][] = $this->title;

?>

<?php Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true])) ?>
    <?php $page = IndexPage::begin(compact('model', 'dataProvider')) ?>

        <?= $page->setSearchFormData(compact(['types', 'brands', 'states'])) ?>
        <?php $page->beginContent('main-actions') ?>
            <?php  if (Yii::$app->user->can('zone.create')) : ?>
                <?= Html::a(Yii::t('hipanel:domain', 'Create zone'), ['@zone/create'], ['class' => 'btn btn-sm btn-success']) ?>
            <?php endif; ?>
        <?php $page->endContent() ?>

        <?php $page->beginContent('sorter-actions') ?>
            <?= $page->renderSorter([
                'attributes' => [
                    'no',
                    'add_grace_period',
                    'add_grace_limit',
                ],
            ]) ?>
        <?php $page->endContent() ?>

        <?php $page->beginContent('bulk-actions') ?>
            <?= $page->renderBulkButton('disable', Yii::t('hipanel', 'Disable')) ?>
            <?= $page->renderBulkButton('enable', Yii::t('hipanel', 'Enable')) ?>
            <?= $page->renderBulkButton('update', Yii::t('hipanel', 'Update')) ?>
            <?= $page->renderBulkDeleteButton('delete') ?>
        <?php $page->endContent('bulk-actions') ?>

        <?php $page->beginContent('table') ?>
            <?php $page->beginBulkForm() ?>
                <?= ZoneGridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $model,
                    'boxed' => false,
                    'columns' => [
                        'checkbox',
                        'registry',
                        'name',
                        'has_contacts',
                        'password_required',
                        'state',
                        'no',
                        'add_grace_period',
                        'add_grace_limit',
                        'autorenew_grace_period',
                        'redemption_grace_period',
                    ],
                ]) ?>
            <?php $page->endBulkForm() ?>
        <?php $page->endContent() ?>
    <?php $page->end() ?>
<?php Pjax::end() ?>

