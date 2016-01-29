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
use hipanel\widgets\AjaxModal;
use hipanel\widgets\Pjax;
use hipanel\widgets\SettingsModal;
use yii\bootstrap\Dropdown;
use yii\bootstrap\Modal;
use yii\helpers\Html;

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
            <div class="dropdown" style="display: inline-block">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?= Yii::t('app', 'Basic actions') ?>
                    <span class="caret"></span>
                </button>
                <?= Dropdown::widget([
                    'encodeLabels' => false,
                    'items' => [
                        ['label' => Yii::t('app', 'Sync'), 'url' => '#', 'linkOptions' => ['data-action' => 'sync'], 'visible' => Yii::$app->user->can('support')],
                        ['label' => Yii::t('app', 'Renew'), 'url' => '#', 'linkOptions' => ['data-action' => 'bulk-renewal']],
                        // Hold
                        '<li role="presentation" class="divider"></li>',
                        ['label' => '<i class="fa fa-toggle-on"></i> ' . Yii::t('app', 'Hold Enable'), 'url' => '#', 'linkOptions' => ['data-action' => 'enable-hold'], 'visible' => Yii::$app->user->can('support')],
                        ['label' => '<i class="fa fa-toggle-off"></i> ' . Yii::t('app', 'Hold Disable'), 'url' => '#', 'linkOptions' => ['data-action' => 'disable-hold'], 'visible' => Yii::$app->user->can('support')],
                        // WHOIS protect
                        '<li role="presentation" class="divider"></li>',
                        ['label' => '<i class="fa fa-toggle-on"></i> ' . Yii::t('app', 'WHOIS protect Enable'), 'url' => '#', 'linkOptions' => ['data-action' => 'enable-whois-protect']],
                        ['label' => '<i class="fa fa-toggle-off"></i> ' . Yii::t('app', 'WHOIS protect Disable'), 'url' => '#', 'linkOptions' => ['data-action' => 'enable-whois-protect']],
                        // Lock
                        '<li role="presentation" class="divider"></li>',
                        ['label' => '<i class="fa fa-toggle-on"></i> ' . Yii::t('app', 'Lock Enable'), 'url' => '#', 'linkOptions' => ['data-action' => 'enable-lock']],
                        ['label' => '<i class="fa fa-toggle-off"></i> ' . Yii::t('app', 'Lock Disable'), 'url' => '#', 'linkOptions' => ['data-action' => 'disable-lock']],
                        // Autorenew
                        '<li role="presentation" class="divider"></li>',
                        ['label' => '<i class="fa fa-toggle-on"></i> ' . Yii::t('app', 'Autorenew Enable'), 'url' => '#', 'linkOptions' => ['data-action' => 'enable-autorenewal']],
                        ['label' => '<i class="fa fa-toggle-off"></i> ' . Yii::t('app', 'Autorenew Disable'), 'url' => '#', 'linkOptions' => ['data-action' => 'disable-autorenewal']],
                    ],
                ]); ?>
            </div>
            <?= AjaxModal::widget([
                'bulkPage' => true,
                'header'=> Html::tag('h4', Yii::t('app', 'Push'), ['class' => 'modal-title']),
                'scenario' => 'domain-push-modal',
                'actionUrl' => ['domain-push-modal'],
                'size' => Modal::SIZE_LARGE,
                'toggleButton' => ['label' => Yii::t('app', 'Push'), 'class' => 'btn btn-default',],
            ]) ?>
            <?= AjaxModal::widget([
                'bulkPage' => true,
                'header'=> Html::tag('h4', Yii::t('app', 'Set notes'), ['class' => 'modal-title']),
                'scenario' => 'bulk-set-note',
                'actionUrl' => ['bulk-set-note'],
                'size' => Modal::SIZE_LARGE,
                'toggleButton' => ['label' => Yii::t('app', 'Set notes'), 'class' => 'btn btn-default',],
            ]) ?>
            <?= AjaxModal::widget([
                'bulkPage' => true,
                'header'=> Html::tag('h4', Yii::t('app', 'Set NS'), ['class' => 'modal-title']),
                'scenario' => 'bulk-set-nss',
                'actionUrl' => ['bulk-set-nss'],
                'size' => Modal::SIZE_LARGE,
                'toggleButton' => ['label' => Yii::t('app', 'Set NS'), 'class' => 'btn btn-default',],
            ]) ?>
            <?= AjaxModal::widget([
                'bulkPage' => true,
                'header'=> Html::tag('h4', Yii::t('app', 'Change contacts'), ['class' => 'modal-title']),
                'scenario' => 'bulk-set-contacts',
                'actionUrl' => ['bulk-set-contacts-modal'],
                'size' => Modal::SIZE_LARGE,
                'toggleButton' => ['label' => Yii::t('app', 'Change contacts'), 'class' => 'btn btn-default',],
            ]) ?>

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
