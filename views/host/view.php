<?php
/**
 * @link    http://hiqdev.com/hipanel-module-domain
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */

use hipanel\modules\domain\grid\HostGridView;
use hipanel\widgets\Pjax;
use yii\helpers\Html;

$this->title    = Html::encode($model->host);
$this->subtitle = Yii::t('app','name server detailed information');
$this->breadcrumbs->setItems([
    ['label' => Yii::t('app', 'Name servers'), 'url' => ['index']],
    $this->title,
]);

?>

<? Pjax::begin(Yii::$app->params['pjax']); ?>
<div class="row" xmlns="http://www.w3.org/1999/html">

<div class="col-md-4">
    <?= HostGridView::detailView([
        'model'   => $model,
        'columns' => [
            'seller_id','client_id',
            ['attribute' => 'host'],'ips',
        ],
    ]) ?>
</div>

</div>
<?php Pjax::end();
