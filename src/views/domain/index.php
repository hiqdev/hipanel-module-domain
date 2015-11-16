<?php
/**
 * @link    http://hiqdev.com/hipanel-module-domain
 *
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */
use hipanel\modules\domain\grid\DomainGridView;
use hipanel\modules\domain\models\Domain;
use hipanel\widgets\ActionBox;
use hipanel\widgets\Pjax;

$this->title    = Yii::t('app', 'Domains');
$this->subtitle = Yii::t('app', array_filter(Yii::$app->request->get($model->formName(), [])) ? 'filtered list' : 'full list');
$this->breadcrumbs->setItems([
    $this->title,
]);
$this->registerCss(<<<CSS
.editable-unsaved {
  font-weight: normal;
}
CSS
);
?>

<?php Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true])) ?>
    <?php $box = ActionBox::begin(['model' => $model, 'dataProvider' => $dataProvider]) ?>
        <?php $box->beginActions() ?>
            <?= $box->renderSearchButton() ?>
            <?= $box->renderSorter([
                'attributes' => [
                    'domain', 'note',
                    'client', 'seller',
                    'state', 'whois_protected', 'is_secured',
                    'created_date', 'expires',
                    'autorenewal', 'id',
                ],
            ]) ?>
            <?= $box->renderPerPage() ?>
        <?php $box->endActions() ?>
        <?php $box->beginBulkActions() ?>
            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?= Yii::t('app', 'WHOIS protect') ?> <span class="caret"></span>
                </button>
                <div class="dropdown-menu dropdown-content">
                    <?= \yii\bootstrap\ButtonGroup::widget([
                        'buttons' => [
                            $box->renderBulkButton(Yii::t('app', 'Enable'), 'enable-whois-protect'),
                            $box->renderBulkButton(Yii::t('app', 'Disable'), 'disable-whois-protect'),
                        ],
                    ]); ?>
                </div>
            </div>

            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?= Yii::t('app', 'Lock') ?> <span class="caret"></span>
                </button>
                <div class="dropdown-menu dropdown-content">
                    <?= \yii\bootstrap\ButtonGroup::widget([
                        'buttons' => [
                            $box->renderBulkButton(Yii::t('app', 'Enable'), 'enable-lock'),
                            $box->renderBulkButton(Yii::t('app', 'Disable'), 'disable-lock'),
                        ],
                    ]); ?>
                </div>
            </div>

            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?= Yii::t('app', 'Autorenew') ?> <span class="caret"></span>
                </button>
                <div class="dropdown-menu dropdown-content">
                    <?= \yii\bootstrap\ButtonGroup::widget([
                        'buttons' => [
                            $box->renderBulkButton(Yii::t('app', 'Enable'), 'enable-autorenewal'),
                            $box->renderBulkButton(Yii::t('app', 'Disable'), 'disable-autorenewal'),
                        ],
                    ]); ?>
                </div>
            </div>
            <?= $this->render('_modalNs') ?>
            <?= $this->render('_modalContacts', ['model' => null]) ?>
            &nbsp;
        <?php $box->endBulkActions() ?>
        <?= $box->renderSearchForm(['stateData' => $stateData]) ?>
    <?php $box->end() ?>
<?php $box->beginBulkForm() ?>
    <?= DomainGridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $model,
        'columns'      => [
            'checkbox',
            'domain',
            'client',
            'seller',
            'state',
            'whois_protected', 'is_secured',
            'created_date', 'expires',
            'autorenewal',
            'actions',
        ],
    ]) ?>
<?php $box->endBulkForm() ?>
<?php Pjax::end() ?>
