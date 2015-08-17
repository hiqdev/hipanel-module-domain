<?php
/**
 * @link    http://hiqdev.com/hipanel-module-domain
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */

use hipanel\modules\domain\grid\DomainGridView;
use hipanel\modules\domain\models\Domain;
use hipanel\widgets\ActionBox;
use hipanel\widgets\BulkButtons;
use hipanel\widgets\LinkSorter;
use hipanel\widgets\Pjax;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\Html;
$this->title    = Yii::t('app', 'Domains');
$this->subtitle = Yii::t('app', Yii::$app->request->queryParams ? 'filtered list' : 'full list');
$this->breadcrumbs->setItems([
    $this->title
]);
$this->registerCss(<<<CSS
.editable-unsaved {
  font-weight: normal;
}
CSS
);
?>

<? Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true])) ?>

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
                            $box->renderBulkButton(Yii::t('app', 'Enable'), ''),
                            $box->renderBulkButton(Yii::t('app', 'Disable'), ''),
                        ]
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
                            $box->renderBulkButton(Yii::t('app', 'Enable'), ''),
                            $box->renderBulkButton(Yii::t('app', 'Disable'), ''),
                        ]
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
                            $box->renderBulkButton(Yii::t('app', 'Enable'), ''),
                            $box->renderBulkButton(Yii::t('app', 'Disable'), ''),
                        ]
                    ]); ?>
                </div>
            </div>
            <?= $this->render('_modalNs') ?>
            <?= $this->render('_modalContacts', ['model' => null]) ?>
            &nbsp;
        <?php $box->endBulkActions() ?>
        <?= $this->render('_search', ['model' => $model, 'stateData' => $stateData]) ?>
    <?php $box::end() ?>
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
<?php $box::endBulkForm() ?>
<? Pjax::end() ?>
