<?php

use hipanel\modules\domain\models\Park;
use hipanel\widgets\Pjax;
use yii\widgets\DetailView;

?>
<?php Pjax::begin([
    'id' => 'park_view',
    'enablePushState' => false,
]) ?>

<?= $this->render('_formParking', ['model' => $model->parking ? : new Park(), 'domain' => $model]) ?>
<?php if ($model->park) : ?>
    <hr>
    <?= DetailView::widget([
        'model' => $model->parking,
        'attributes' => ['title', 'siteheader', 'sitetext'],
    ]) ?>
<?php endif; ?>

<?php Pjax::end() ?>
