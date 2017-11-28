<?php

/** @var array $forwardingOptions */

use hipanel\modules\domain\grid\UrlfwGridView;
use hipanel\modules\domain\models\Urlfw;
use hipanel\widgets\Pjax;
use yii\data\ArrayDataProvider;

?>
<?php Pjax::begin([
    'id' => 'url_fw_view',
    'enablePushState' => false,
]) ?>

<?=
$this->render('_formUrlfw', ['model' => new Urlfw, 'domain' => $model, 'forwardingOptions' => $forwardingOptions]) ?>
<hr>
<?= UrlfwGridView::widget([
    'dataProvider' => new ArrayDataProvider([
        'allModels' => $model->urlfws,
        'modelClass' => Urlfw::class,
        'pagination' => ['pageSize' => count($model->mailfws)],
    ]),
    'emptyText' => Yii::t('hipanel:domain', 'URL forwarding is not configured'),
    'domain' => $model->domain,
    'is_premium' => $model->premium->is_active,
    'columns' => ['name', 'type_label', 'value', 'actions'],
]) ?>

<?php Pjax::end() ?>
