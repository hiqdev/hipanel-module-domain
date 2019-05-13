<?php

/**
 * @var \yii\web\View $this
 */

$this->title = Yii::t('hipanel:domain', 'Create zone');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:domain', 'Zone'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('_form', compact(['model', 'models'])) ?>
