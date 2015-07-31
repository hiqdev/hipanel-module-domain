<?php

$this->title = Yii::t('app', 'Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Name Server'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php \hipanel\widgets\Box::begin() ?>
<?= $this->render('_form', ['models' => $models]); ?>
<?php \hipanel\widgets\Box::end() ?>

