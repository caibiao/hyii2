<?php
/**
 * @author CaiBiao Shen <syu150107@gmail.com>
 * @date 2017/11/10
 */

namespace backend\Modules\News;

/**
 * datadict module definition class
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\Modules\News\Controllers';

    public function init()
    {
        parent::init();
        // custom initialization code goes here
        $this->viewPath = '@backend/views';
    }
}
