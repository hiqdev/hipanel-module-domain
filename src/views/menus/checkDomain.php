<?php

use yii\helpers\Url;

?>

<?php $searchUrl = Url::to('@domain-check/check-domain') ?>
<form action="<?= $searchUrl ?>" method="get" class="sidebar-form">
    <div class="input-group">
        <input type="text" name="fqdn" class="form-control" placeholder="<?= Yii::t('hipanel:domain', 'Check domain') ?>..."/>
        <span class="input-group-btn">
            <button type='submit' id='search-btn' class="btn btn-flat">
                <i class="fa fa-search"></i>
            </button>
        </span>
    </div>
</form>
