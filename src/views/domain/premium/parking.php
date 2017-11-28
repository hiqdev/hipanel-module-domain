<?php

use hipanel\modules\domain\models\Park;
use hipanel\widgets\Pjax;
use yii\bootstrap\Html;
use yii\widgets\DetailView;

?>
<?php Pjax::begin([
    'id' => 'park_view',
    'enablePushState' => false,
]) ?>

<?= $this->render('_formParking', ['model' => $model->parking ?: new Park(), 'domain' => $model]) ?>
<?php if ($model->park) : ?>
    <hr>
    <?= DetailView::widget([
        'model' => $model->parking,
        'attributes' => [
            [
                'attribute' => 'type_id',
                'format' => 'html',
                'value' => Html::img('https://ahnames.com/www/img/parking/park' . $model->parking->type_id . '.png', ['width' => 130]),
            ],
            'title',
            'siteheader',
            'sitetext',
        ],
    ]) ?>
<?php endif; ?>

<?php Pjax::end() ?>
