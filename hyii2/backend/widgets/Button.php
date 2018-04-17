<?php
/**
 *
 * @date 2016-12-13
 */

namespace abiao\widgets;

use yii\bootstrap\Button as BootstrapButton;
use yii\bootstrap\Html;

/**
 * Button renders a button with Role Auth
 *
 * For example,
 *
 * ```php
 * echo Button::widget([
 *     'label' => 'Action',
 *     'options' => ['class' => 'btn-lg'],
 * ]);
 * ```
 */

class Button extends BootstrapButton
{

    public $route = '';

    /**
     * Initializes the widget.
     * If you override this method, make sure you call the parent implementation first.
     */
    public function init()
    {
        parent::init();
        $this->clientOptions = false;
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        $this->registerPlugin('button');
        $auth = true;
        if (!empty($this->route)) {
            $route = is_array($this->route) ? $this->route[0] : $this->route;
            $auth = \mdm\admin\components\Helper::checkRoute($route);
        }
        return $auth ? Html::tag($this->tagName, $this->encodeLabel ? Html::encode($this->label) : $this->label, $this->options) : '';
    }
}
