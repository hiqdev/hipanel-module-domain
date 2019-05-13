<?php

/**
 * @var \yii\web\View $this
 * @var \hipanel\modules\domain\models\Zone $model
 */

$this->title = Yii::t('hipanel', 'Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel', 'Update'), 'url' => ['index']];
if (count($models) === 1) {
    $this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
}

?>

<?= $this->render('_form', compact(['model', 'models'])) ?>

