<?php
/**
 * @link    http://hiqdev.com/hipanel-module-domain
 *
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */
use hipanel\modules\domain\grid\HostGridView;
use hipanel\widgets\Box;
use hipanel\widgets\ClientSellerLink;
use hipanel\widgets\Pjax;
use yii\helpers\Html;

$this->title    = Html::encode($model->host);
$this->subtitle = Yii::t('app', 'Name server detailed information') . ' #' . $model->id;
$this->breadcrumbs->setItems([
    ['label' => Yii::t('app', 'Name servers'), 'url' => ['index']],
    $this->title,
]);

?>

<?php Pjax::begin() ?>
    <div class="row" xmlns="http://www.w3.org/1999/html">
        <div class="col-md-3">
            <?php Box::begin([
                'options' => [
                    'class' => 'box-solid',
                ],
                'bodyOptions' => [
                    'class' => 'no-padding',
                ],
            ]) ?>
            <div class="profile-user-img text-center">
                <i class="fa fa-globe" style="font-size:7em"></i>
            </div>
            <p class="text-center">
                <span class="profile-user-role"><?= $this->title ?></span>
                <br>
                <span class="profile-user-name"><?= ClientSellerLink::widget(compact('model')) ?></span>
            </p>

            <div class="profile-usermenu">
                <ul class="nav">
                    <li>
                        <?= Html::a('<i class="ion-close-circled"></i>' . Yii::t('app', 'Delete'), ['delete', 'id' => $model->id]) ?>
                    </li>
                </ul>
            </div>
            <?php Box::end() ?>
        </div>

        <div class="col-md-4">
            <?= HostGridView::detailView([
                'model'   => $model,
                'columns' => [
                    'seller_id', 'client_id',
                    'domain', 'bold_host', 'ips',
                ],
            ]) ?>
        </div>
    </div>
<?php Pjax::end() ?>
