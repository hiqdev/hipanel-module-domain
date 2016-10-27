<?php

$this->title = Yii::t('hipanel/domain', 'Create name server');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel', 'Name Server'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('_form', ['models' => $models]) ?>
