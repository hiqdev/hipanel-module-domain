<?php

use hipanel\modules\domain\models\Park;
use hipanel\widgets\Pjax;
use yii\widgets\DetailView;

?>
<?php Pjax::begin([
    'id' => 'park_view',
    'enablePushState' => false,
]) ?>

<?= $this->render('_formPark', ['model' => new Park(), 'domain' => $model]) ?>
<?php if ($model->park) : ?>
    <hr>
    <?= DetailView::widget([
        'model' => $model->park,
        'attributes' => ['title', 'siteheader', 'sitetext'],
    ]) ?>
<?php endif; ?>

<?php Pjax::end() ?>
