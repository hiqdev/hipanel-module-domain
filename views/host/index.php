<?php
/**
 * @link    http://hiqdev.com/hipanel-module-domain
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */

use hipanel\modules\domain\grid\HostGridView;
use hipanel\widgets\ActionBox;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title    = Yii::t('app', 'Name Servers');
$this->subtitle = Yii::t('app', Yii::$app->request->queryParams ? 'filtered list' : 'full list');
$this->breadcrumbs->setItems([
    $this->title
]);

?>
<?= Html::beginForm('', '') ?>
<?php $box = ActionBox::begin(['bulk' => true, 'options' => ['class' => 'box-info']]) ?>
<?php $box->beginActions() ?>
<?= Html::a(Yii::t('app', 'Create {modelClass}', ['modelClass' => Yii::t('app', 'Name server')]), ['create'], ['class' => 'btn btn-primary']) ?>&nbsp;
<?php $box->endActions() ?>
<?php $box->beginBulkActions() ?>
<?= Html::submitButton(Yii::t('app', 'Change IP'), ['id' => 'change-ip-button', 'class' => 'btn btn-primary', 'formmethod' => 'GET', 'formaction' => Url::to('update')]) ?>
&nbsp;
<?= Html::submitButton(Yii::t('app', 'Delete'), ['id' => 'change-ip-button', 'class' => 'btn btn-danger', 'formmethod' => 'POST', 'formaction' => Url::to('delete')]) ?>
<?php $box->endBulkActions() ?>

<?php $box::end() ?>


<?= HostGridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns'      => [
        'host', 'ips',
        'client_id', 'seller_id',
        'checkbox',
    ],
]) ?>
<?= Html::endForm() ?>
