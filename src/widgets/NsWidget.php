<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\widgets;

use hipanel\modules\domain\models\Ns;
use yii\base\Widget;

class NsWidget extends Widget
{
    public $model;

    public $attribute;

    public $domainId;

    /**
     * @var array options for [[Pjax]] widget
     */
    public $pjaxOptions = [];

    /**
     * @var array options for [[Progress]] widget
     */
    public $progressOptions = [];

    /**
     * @var array|string url to send the form
     */
    public $actionUrl = 'set-nss';

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $nsModels = $this->createNsModels($this->model->{$this->attribute});

        return $this->render('ns', [
            'model' => $this->model,
            'attribute' => $this->attribute,
            'nsModels' => $nsModels,
            'actionUrl' => $this->actionUrl,
        ]);
    }

    private function createNsModels($nss)
    {
        $models = [];
        if (!empty($nss)) {
            foreach (explode(',', $nss) as $item) {
                if (strpos($item, '/') !== false) {
                    $ns_ip  = explode('/', $item);
                    $data['Ns'] = [
                        'name' => $ns_ip[0],
                        'ip' => explode(';', $ns_ip[1]),
                    ];
                } else {
                    $data['Ns']['name'] = $item;
                }
                $model = new Ns();
                $model->load($data, 'Ns');
                $model->validate();
                $models[] = $model;
            }
        } else {
            $models[] = new Ns();
        }

        return $models;
    }
}
