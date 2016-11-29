<?php

namespace hipanel\modules\domain\menus;

use hiqdev\menumanager\Menu;

class HostDetailMenu extends Menu
{
    public $model;

    public function items()
    {
        $actions = HostActionsMenu::create(['model' => $this->model])->items();
        $items = array_merge($actions, []);

        return $items;
    }
}
