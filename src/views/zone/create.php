<?php

$this->title = Yii::t('hipanel:domain', 'Create Zone');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel', 'Zone'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('_form', compact(['model', 'models'])) ?>
