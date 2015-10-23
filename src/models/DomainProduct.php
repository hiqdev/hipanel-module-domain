<?php

namespace hipanel\modules\domain\models;

use hipanel\models\CartPosition;

class DomainProduct extends CartPosition
{
    /**
     * @var Domain
     */
    public $model;

    public $icon = '<i class="fa fa-server text-muted"></i>';

//    public function getName() {
//        return $this->model->domain;
//    }
//
//    public function getDescription() {
//        return "Покупка домена " . $this->model->domain;
//    }
//
//    public function getScenario() {
//
//    }
//
//    public function setScenario($scenario) {
//
//    }
}