<?php

/**
 * @var \yii\web\View $this
 * @var array $models
 * @var \hipanel\modules\domain\models\Zone $model
 */

$this->title = Yii::t('hipanel:domain', 'Create zone');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:domain', 'Zone'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('_form', compact(['model', 'models'])) ?>
