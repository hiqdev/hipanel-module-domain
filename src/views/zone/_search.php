<?php

use hipanel\modules\domain\widgets\combo\RegistryCombo;
use hipanel\widgets\RefCombo;

/**
 * @var \hipanel\widgets\AdvancedSearch $search
 * @var \yii\web\View $this
 */

?>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('registry')->widget(RegistryCombo::class)?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('state')->widget(RefCombo::class, [
        'gtype' => 'state,zone',
        'multiple' => false,
    ]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('name_ilike') ?>
</div>
