<?php

/** @var string $sShotSrc */
/** @var array $whoisData */
/** @var \hipanel\modules\domain\models\Domain $model */

use hipanel\widgets\ArraySpoiler;
use yii\helpers\Html;
use yii\widgets\DetailView;

?>
<?php if ($whoisData) : ?>
    <div class="row">
        <div class="col-md-3">
            <div class="md-mb-10">
                <span class="mailbox-attachment-icon has-img"><?= Html::img($sShotSrc, ['alt' => $model->domain]) ?></span>
                <div class="mailbox-attachment-info">
                    <?= Html::a('<i class="fa fa-globe"></i>&nbsp;&nbsp;' . $model->domain, $model->domain, ['class' => 'mailbox-attachment-name']) ?>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <?= DetailView::widget([
                'model' => $whoisData,
                'attributes' => [
                    [
                        'label' => Yii::t('hipanel', 'Domain'),
                        'value' => Html::encode($whoisData['domain']),
                    ],
                    [
                        'label' => Yii::t('hipanel/domainchecker', 'Created'),
                        'value' => Yii::$app->formatter->asDate($whoisData['created']),
                        'visible' => $whoisData['created'] !== null,
                    ],
                    [
                        'label' => Yii::t('hipanel/domainchecker', 'Updated'),
                        'value' => Yii::$app->formatter->asDate($whoisData['updated']),
                        'visible' => $whoisData['updated'] !== null,
                    ],
                    [
                        'label' => Yii::t('hipanel/domainchecker', 'Expires'),
                        'value' => Yii::$app->formatter->asDate($whoisData['expires']),
                        'visible' => $whoisData['expires'] !== null,
                    ],
                    [
                        'label' => Yii::t('hipanel/domainchecker', 'Registrar'),
                        'value' => Html::encode($whoisData['registrar']),
                        'visible' => $whoisData['registrar'] !== null,
                    ],
                    [
                        'label' => Yii::t('hipanel/domainchecker', 'NS'),
                        'value' => ArraySpoiler::widget([
                            'data' => $whoisData['nss'],
                            'visibleCount' => count($whoisData['nss']),
                            'formatter' => function ($ip, $ns) {
                                return $ns . ' - ' . $ip;
                            },
                            'delimiter' => '<br>',
                        ]),
                        'format' => 'html',
                    ],
                    [
                        'label' => Yii::t('hipanel/domainchecker', 'IP'),
                        'value' => $whoisData['ip'],
                    ],
                    [
                        'label' => Yii::t('hipanel/domainchecker', 'Country'),
                        'value' => $whoisData['country_name'],
                    ],
                    [
                        'label' => Yii::t('hipanel/domainchecker', 'City'),
                        'value' => $whoisData['city'],
                        'visible' => $whoisData['city'] !== '',
                    ],
                ]
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= ArraySpoiler::widget([
                'data' => $whoisData['rawdata'],
                'visibleCount' => count($whoisData['rawdata']),
                'delimiter' => '<br>',
            ]) ?>
        </div>
    </div>
<?php else: ?>
    <div class="bg-danger text-center">
        <?= Yii::t('hipanel/domainchecker', 'You have entered wrong domain name or domain name with unsupported zone.') ?>
    </div>
<?php endif; ?>
