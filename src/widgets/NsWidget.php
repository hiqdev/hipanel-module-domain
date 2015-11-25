<?php

namespace hipanel\modules\domain\widgets;

use hipanel\modules\domain\models\Ns;
use Yii;
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
     * {@inheritdoc}
     */
    public function run()
    {
        $nsModels = $this->createNsModels($this->model->{$this->attribute});

        return $this->render('ns', [
            'model' => $this->model,
            'attribute' => $this->attribute,
            'nsModels' => $nsModels,
        ]);
    }


    private function createNsModels($string)
    {
        $models = [];
        foreach (explode(',', $string) as $item) {
            if (strpos($item, '/')) {
                $ns_ip  = explode('/', $item);
                $data['Ns'] = [
                    'name' => $ns_ip[0],
                    'ip' => $ns_ip[1],
                ];
            } else {
                $data['Ns']['name'] = $item;
            }
            $model = new Ns;
            $model->load($data, 'Ns');
            $model->validate();
            $models[] = $model;
        }

        return $models;
    }
}