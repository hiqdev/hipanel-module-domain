<?php
/**
 * @link    http://hiqdev.com/hipanel-module-domain
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */

use hipanel\modules\domain\grid\DomainGridView;

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
<?= ?>
<?= DomainGridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns'      => [
        'domain',
        'seller_id',
        'client_id',
        'state',
        'whois_protected',
        'is_secured',
//        'note',
        'created_date',
        'expires',
        'autorenewal',
        'actions',
        'checkbox',
    ],
]) ?>
