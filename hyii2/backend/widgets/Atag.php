<?php
/**
 *
 * @date 2016-12-13
 */

namespace abiao\widgets;

use yii\bootstrap\Html as BootstrapHtml;
use yii\bootstrap\Widget as BootstrapWidget;
use yii\helpers\Url;

/**
 * A tag renders a button with Role Auth
 *
 * For example,
 *
 * ```php
 * echo Atag::widget([
 *     'text' => 'Action',
 *     'options' => ['class' => 'btn-lg'],
 * ]);
 * ```
 */

class Atag extends BootstrapWidget
{

    public $text = 'abiao';
    public $route = '';
    public $visabled = true; // 无权限时有效， true 显示/ false 不显示

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
        $this->registerPlugin('atag');
        $auth = true;
        unset($this->options['href']);
        if (!empty($this->route)) {
            $route = is_array($this->route) ? $this->route[0] : $this->route;
            $auth = \mdm\admin\components\Helper::checkRoute($route);
            if ($auth) {
                $this->options['href'] = Url::to($this->route);
            } else {
                $class = empty($this->options['class']) ? '' : $this->options['class'];
                $class .= ' disabled';
                $this->options['class'] = $class;
            }
        }

        $content = '';
        if ($auth || $this->visabled) {
            $content .= BootstrapHtml::beginTag('a', $this->options);
            $content .= BootstrapHtml::tag('sapn', $this->text, ['class' => $auth ? '' : 'disabled']);
            $content .= BootstrapHtml::endTag('a');
        }

        return $content;
    }
}
