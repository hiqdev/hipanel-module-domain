<?php
/**
 * @link    http://hiqdev.com/hipanel-module-domain
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */

use hipanel\modules\domain\grid\HostGridView;

$this->title    = Yii::t('app', 'Name Servers');
$this->subtitle = Yii::t('app', Yii::$app->request->queryParams ? 'filtered list' : 'full list');
$this->breadcrumbs->setItems([
    $this->title
]);

?>

<?= HostGridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns'      => [
        'checkbox',
        'seller_id','client_id',
        'host','ips',
    ],
]) ?>
