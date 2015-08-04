<?php

use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\modules\client\widgets\combo\SellerCombo;

?>

<div class="col-md-6">
    <?= $search->field('host_like') ?>
    <?= $search->field('domain_like') ?>
</div>

<div class="col-md-6">
    <?= $search->field('client_id')->widget(ClientCombo::classname()) ?>
    <?= $search->field('seller_id')->widget(SellerCombo::classname()) ?>
</div>
