<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

use yii\bootstrap\Html;
use yii\bootstrap\Modal;

/**
 * @var \yii\web\View $this
 */
?>

<?php Modal::begin([
    'id' => 'service-termination-notice-modal',
    'header' => '<h4>Service termination notice</h4>',
    'footer' => Html::checkbox('service-termination-notice-dont-show-again', false, [
            'id' => 'service-termination-notice-dont-show-again',
            'label' => 'I understand, don\'t show this notice again.',
        ])
        . Html::button('Close', [
            'class' => 'btn btn-primary service-termination-notice-close',
        ]),
    'size' => Modal::SIZE_DEFAULT,
    'closeButton' => false,
]) ?>

<p>
    Please be advised that effective <strong>August 5, 2026</strong>, the registration of new
    domains through our service will be suspended.
</p>

<p>
    If you wish to register a new domain, we recommend using ICANN-accredited registrar with a
    solid reputation at the following link:
    <?= Html::a('AdvancedHosting', 'https://portal.advancedhosting.com/cart.php?a=add&domain=register', ['target' => '_blank']) ?>.
</p>

<p>
    Please note that this update is made in accordance with our
    <?= Html::a('Master Service Agreement', 'https://ahnames.com/en/rules', ['target' => '_blank']) ?>,
    which states that We reserve the right to modify, change, or discontinue any aspect of the
    Website or the Services. Please rest assured that this change does not affect your existing
    domains or active services, which will continue to operate as usual.
</p>

<p>
    Should you have any questions or require assistance, please do not hesitate to contact our
    support team.
</p>

<p>
    Best regards,<br>
    AHnames Team
</p>

<?php Modal::end() ?>
