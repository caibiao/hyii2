<?php

namespace backend\Modules\DataOp;

/**
 * Custom module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\Modules\DataOp\Controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->viewPath = '@backend/views/dataOp';

        // custom initialization code goes here
    }
}
