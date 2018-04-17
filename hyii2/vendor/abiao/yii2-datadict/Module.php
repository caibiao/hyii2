<?php
/**
 * @author CaiBiao Shen <syu150107@gmaik.com>
 * @date 2017/11/10
 */

namespace abiao\datadict;

/**
 * datadict module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'abiao\datadict\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
