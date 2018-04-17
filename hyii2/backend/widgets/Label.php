<?php
/**
 *
 * @date 2016-12-26
 */

namespace abiao\widgets;

use abiao\order\components\rbac\Helper as RbacHelper;
use yii\bootstrap\Html as BootstrapHtml;
use yii\bootstrap\Widget as BootstrapWidget;

/**
 * Label renders a Label with Role Auth
 *
 * For example,
 *
 * ```php
 * echo Label::widget([
 *     'text' => 'value',
 *     'authName'  => \abiao\order\components\rbac\Helper::AUTH_VIEW_COSTPRICE
 *     'defaultText' => '--',
 *     'field' => 'costPrice',
 *     'options' => ['class' => 'className'],
 * ]);
 * ```
 */

class Label extends BootstrapWidget
{

    // 有权限时显示的文字内容
    public $text = 'text';

    // 需要控制权限的字段名称
    public $field = '';

    // 权限名称，使用 abiao\order\components\rbac\Helper::AUTH_XXXX等常量
    public $authName = '';

    // 无权限时显示的内容，支持html
    public $defaultText = '*****';

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
        $this->registerPlugin('AnlewoAuthLabel');

        if (RbacHelper::checkAuthByFieldName($this->authName, $this->field)) {
            return BootstrapHtml::tag('span', $this->text, $this->options);
        }

        $this->options['data-toggle'] = "tooltip";
        $this->options['data-original-title'] = "您没有查看权限";
        $this->options['class'] = 'text-muted';
        return BootstrapHtml::tag('span', $this->defaultText, $this->options);
    }
}
