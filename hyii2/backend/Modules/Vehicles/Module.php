<?php

namespace backend\Modules\Vehicles;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\Modules\Vehicles\Controllers';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
        $this->viewPath = '@backend/views';
    }
}
