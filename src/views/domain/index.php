<?php

use hipanel\modules\domain\grid\DomainGridView;
use hipanel\widgets\ActionBox;
use hipanel\widgets\AjaxModal;
use hipanel\widgets\Pjax;
use yii\bootstrap\Dropdown;
use yii\bootstrap\Modal;
use yii\helpers\Html;

$this->title    = Yii::t('hipanel', 'Domains');
$this->subtitle = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
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
                    <?= Yii::t('hipanel', 'Basic actions') ?>
                    <span class="caret"></span>
                </button>
                <?= Dropdown::widget([
                    'encodeLabels' => false,
                    'items' => [
                        ['label' => Yii::t('hipanel/domain', 'Sync contacts'), 'url' => '#', 'linkOptions' => ['data-action' => 'sync'], 'visible' => Yii::$app->user->can('support')],
                        ['label' => Yii::t('hipanel/domain', 'Renew'), 'url' => '#', 'linkOptions' => ['data-action' => 'bulk-renewal']],
                        ['label' => Yii::t('hipanel/domain', 'Push domain'), 'url' => '#bulk-domain-push-modal', 'linkOptions' => ['data-toggle' => 'modal']],
                    // Hold
                        '<li role="presentation" class="divider"></li>',
                        ['label' => '<i class="fa fa-toggle-on"></i> ' . Yii::t('hipanel/domain', 'Enable Hold'), 'url' => '#', 'linkOptions' => ['data-action' => 'enable-hold'], 'visible' => Yii::$app->user->can('support')],
                        ['label' => '<i class="fa fa-toggle-off"></i> ' . Yii::t('hipanel/domain', 'Disable Hold'), 'url' => '#', 'linkOptions' => ['data-action' => 'disable-hold'], 'visible' => Yii::$app->user->can('support')],
                        // WHOIS protect
                        '<li role="presentation" class="divider"></li>',
                        ['label' => '<i class="fa fa-toggle-on"></i> ' . Yii::t('hipanel/domain', 'Enable WHOIS protect'), 'url' => '#', 'linkOptions' => ['data-action' => 'enable-whois-protect']],
                        ['label' => '<i class="fa fa-toggle-off"></i> ' . Yii::t('hipanel/domain', 'Disable WHOIS protect'), 'url' => '#', 'linkOptions' => ['data-action' => 'enable-whois-protect']],
                        // Lock
                        '<li role="presentation" class="divider"></li>',
                        ['label' => '<i class="fa fa-toggle-on"></i> ' . Yii::t('hipanel/domain', 'Enable Lock'), 'url' => '#', 'linkOptions' => ['data-action' => 'enable-lock']],
                        ['label' => '<i class="fa fa-toggle-off"></i> ' . Yii::t('hipanel/domain', 'Disable Lock'), 'url' => '#', 'linkOptions' => ['data-action' => 'disable-lock']],
                        // Autorenew
                        '<li role="presentation" class="divider"></li>',
                        ['label' => '<i class="fa fa-toggle-on"></i> ' . Yii::t('hipanel/domain', 'Enable autorenew'), 'url' => '#', 'linkOptions' => ['data-action' => 'enable-autorenewal']],
                        ['label' => '<i class="fa fa-toggle-off"></i> ' . Yii::t('hipanel/domain', 'Disable autorenew'), 'url' => '#', 'linkOptions' => ['data-action' => 'disable-autorenewal']],
                    ]
                ]); ?>
            </div>
            <?= AjaxModal::widget([
                'id' => 'bulk-domain-push-modal',
                'bulkPage' => true,
                'header' => Html::tag('h4', Yii::t('hipanel/domain', 'Push'), ['class' => 'modal-title']),
                'scenario' => 'domain-push-modal',
                'actionUrl' => ['domain-push-modal'],
                'size' => Modal::SIZE_LARGE,
                'toggleButton' => false,
            ]) ?>
            <?= AjaxModal::widget([
                'id' => 'bulk-set-note-modal',
                'bulkPage' => true,
                'header' => Html::tag('h4', Yii::t('hipanel/domain', 'Set notes'), ['class' => 'modal-title']),
                'scenario' => 'bulk-set-note',
                'actionUrl' => ['bulk-set-note'],
                'size' => Modal::SIZE_LARGE,
                'toggleButton' => ['label' => Yii::t('hipanel/domain', 'Set notes'), 'class' => 'btn btn-default'],
            ]) ?>
            <?= AjaxModal::widget([
                'id' => 'bulk-set-nss-modal',
                'bulkPage' => true,
                'header' => Html::tag('h4', Yii::t('hipanel/domain', 'Set NS'), ['class' => 'modal-title']),
                'scenario' => 'bulk-set-nss',
                'actionUrl' => ['bulk-set-nss'],
                'size' => Modal::SIZE_LARGE,
                'toggleButton' => ['label' => Yii::t('hipanel/domain', 'Set NS'), 'class' => 'btn btn-default'],
            ]) ?>
            <?= AjaxModal::widget([
                'id' => 'bulk-change-contacts-modal',
                'bulkPage' => true,
                'header' => Html::tag('h4', Yii::t('hipanel/domain', 'Change contacts'), ['class' => 'modal-title']),
                'scenario' => 'bulk-set-contacts',
                'actionUrl' => ['bulk-set-contacts-modal'],
                'size' => Modal::SIZE_LARGE,
                'toggleButton' => ['label' => Yii::t('hipanel/domain', 'Change contacts'), 'class' => 'btn btn-default'],
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
