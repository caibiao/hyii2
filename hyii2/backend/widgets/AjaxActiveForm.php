<?php
/**
 * Ajax提交表单
 * @copyright 本软件和相关文档仅限 安乐窝 和/或其附属公司开发团队内部交流使用，
 *            并受知识产权法的保护。除非公司以适用法律明确授权，否则不得以任何形
 *            式、任何方式使用、拷贝、复制、翻译、广播、修改、授权、传播、分发、
 *            展示、执行、发布或显示本软件和相关文档的任何部分。
 * @author    林猛锋<linmengfeng@anlewo.com>
 * @date      2018/2/4 17:22
 */

namespace abiao\widgets;


use light\widgets\AjaxFormAsset;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * Usage:
 * ~~~
 * use light\widgets\ActiveForm;
 * use yii\web\JsExpression;.
 *
 * ActiveForm::begin([
 *     'ajaxSubmitOptions' => [
 *         'success' => new JsExpression('function(response) {//...}'),
 *         'complete' => new JsExpression('function(xhr, msg, $form) {//..}'),
 *         'beforeSubmit' => new JsExpression('function(arr, $form) {//...}')
 *     ]
 * ])
 *
 * ~~~
 *
 */

class AjaxActiveForm extends \kartik\widgets\ActiveForm
{
    /**
     * @var bool If enable the ajax submit
     */
    public $enableAjaxSubmit = true;
    /**
     * @var array The options passed to jquery.form, Please see the jquery.form document
     */
    public $ajaxSubmitOptions = [];
    /**
     * @var string For `yii\bootstrap\ActiveForm` compatibility.
     */
    public $layout;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->layout) {
            if (!in_array($this->layout, ['default', 'horizontal', 'inline'])) {
                throw new InvalidConfigException('Invalid layout type: ' . $this->layout);
            }
            if ($this->layout !== 'default') {
                Html::addCssClass($this->options, 'form-' . $this->layout);
            }
        }
        $this->ajaxSubmitOptions = ArrayHelper::merge(
            [
                'beforeSubmit' => new JsExpression('function(arr, $form) { return ajaxFormBeforeSubmit(arr, $form);}'),
                'success' => new JsExpression('function(response, xhr, msg, $form) { ajaxFormSuccess(response);}'),
                'error' => new JsExpression('function() { ajaxFormError(); }')
            ],
            $this->ajaxSubmitOptions
        );

        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        parent::run();

        if ($this->enableAjaxSubmit) {
            $id = $this->options['id'];
            $view = $this->getView();
            AjaxFormAsset::register($view);
            $_options = Json::htmlEncode($this->ajaxSubmitOptions);
            $view->registerJs("jQuery('#$id').yiiActiveForm().on('beforeSubmit', function(_event) { jQuery(_event.target).ajaxSubmit($_options); return false;});");
        }
    }
}