<?php
/**
 * 详情页表单
 * @copyright 本软件和相关文档仅限 安乐窝 和/或其附属公司开发团队内部交流使用，
 *            并受知识产权法的保护。除非公司以适用法律明确授权，否则不得以任何形
 *            式、任何方式使用、拷贝、复制、翻译、广播、修改、授权、传播、分发、
 *            展示、执行、发布或显示本软件和相关文档的任何部分。
 * @author    林猛锋<linmengfeng@anlewo.com>
 * @date      2018/3/13 09:33
 */

namespace abiao\Order\Widgets;



use abiao\widgets\AjaxActiveForm;
use kartik\depdrop\DepDrop;
use kartik\widgets\ColorInput;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use Yii;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;

class DetailActiveForm extends AjaxActiveForm
{
    /**
     * 详情模型
     * @var ActiveRecord
     */
    public $detailModel;


    public static function begin($config = [])
    {
        $config = ArrayHelper::merge([
            'fullSpan' => 10,
            'formConfig' => [
                'labelSpan' => 3
            ],
            'options' => [
                'enctype' => 'multipart/form-data',
                'class' => 'form-horizontal form-detail',
            ]
        ], $config);
        return parent::begin($config);
    }

    /**
     * 没有label的字段配置
     * @return array
     */
    public static function notLabelOptions()
    {
        return  [
            'class' => 'abiao\Order\Widgets\ActiveField',
            'template' => '{input}{error}',
            'options' => ['class' => 'col-md-12 no-padding'],
        ];
    }

    /**
     * 静态文本输出
     * @param string $attribute model字段
     * @param string $value 值
     * @param array $options 配置项
     * @return mixed
     */
    public function fieldStatic($attribute, $value = null, $options = [])
    {
        $value = empty($value) ? $this->detailModel->$attribute : $value;
        return $this->field($this->detailModel, $attribute, [
            'staticValue' => empty($value) ? '--' : $value,
        ])->staticInput($options);
    }

    /**
     * 编号字段输出
     * @param string $attribute model字段
     * @return mixed
     */
    public function fieldCode($attribute)
    {
        $value = $this->detailModel->isNewRecord ? '自动编号' : $this->detailModel->$attribute;
        $options = $this->detailModel->isNewRecord ? ['style' => 'color:#999'] : [];
        return $this->fieldStatic($attribute, $value, $options);
    }

    /**
     * 静态时间字段输出
     * @param string $attribute model字段
     * @return mixed
     */
    public function fieldStaticDateTime($attribute)
    {
        try {
            $value = empty($this->detailModel->$attribute) ? '--' : Yii::$app->formatter->asDatetime($this->detailModel->$attribute);
        } catch (\Exception $e) {
            $value = '';
        }
        return $this->fieldStatic($attribute, $value);
    }

    /**
     * 静态时间字段输出
     * @param string $attribute model字段
     * @return mixed
     */
    public function fieldStaticDate($attribute)
    {
        try {
            $value = empty($this->detailModel->$attribute) ? '--' : Yii::$app->formatter->asDate($this->detailModel->$attribute);
        } catch (\Exception $e) {
            $value = '';
        }
        return $this->fieldStatic($attribute, $value);
    }


    /**
     * 文本输入框输出
     * @param string $attribute model字段
     * @param array $options 配置项
     * @return \yii\widgets\ActiveField
     */
    public function fieldInput($attribute, $options = [])
    {
        return $this->field($this->detailModel, $attribute, $options);
    }

    /**
     * 下拉框输出
     * @param string $attribute model字段
     * @param array $data 下拉框选项
     * @param array $options 配置项
     * @return mixed
     */
    public function fieldDropDown($attribute, $data, $options = [])
    {
        $options = ArrayHelper::merge(
            [
                'inputOptions' => [
                    'class' => 'form-control select-warp-option',
                ]
            ], $options);
        $data = ArrayHelper::merge(['' => '请选择'], $data);
        return $this->outPut(
            $this->field($this->detailModel, $attribute, $options)->dropDownList($data)
        );
    }

    /**
     * 单选项下拉框
     * @param string $attribute model字段
     * @param array $data 选项
     * @param array $options
     * @param array $widgetOptions
     */
    public function fieldRadioList($attribute, $data, $options = [], $widgetOptions = [])
    {
        return $this->outPut(
            $this->field($this->detailModel, $attribute, $options)->radioList($data, $widgetOptions)
        );
    }

    /**
     * Select2下拉框
     * @param string $attribute model字段
     * @param array $options 配置项
     * @param array $widgetOptions select2配置项
     * @return mixed
     */
    public function fieldSelect2($attribute, $options = [], $widgetOptions = [])
    {
        $widgetOptions = ArrayHelper::merge([
            'data' => [],
            'options' => [
                'multiple' => false,
                'placeholder' => '请选择',
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ], $widgetOptions);
        $model = isset($options['model']) && !empty($options['model']) ? $options['model'] : $this->detailModel;
        return $this->outPut(
            $this->field($model, $attribute, $options)->widget(Select2::classname(), $widgetOptions)
        );
    }

    /**
     * 省份下拉框
     * @param string $attribute model字段
     * @param array $options 配置项
     * @param array $widgetOptions Select2配置
     * @return mixed
     */
    public function fieldProvince($attribute = 'province', $options = [], $widgetOptions = [])
    {
        $options = ArrayHelper::merge([
            'class' => 'abiao\Order\Widgets\ActiveField',
            'template' => '{input}{error}',
            'options' => ['class' => 'col-md-4 no-padding'],
        ], $options);
        $widgetOptions = ArrayHelper::merge([
            'data' =>  Area::getChildList(0),
            'options' => [
                'placeholder' => '请选择省份',
            ]
        ], $widgetOptions);
        return $this->fieldSelect2($attribute, $options, $widgetOptions);
    }

    /**
     * 省市区依赖下拉框
     * @param string $attribute model字段
     * @param array $options 配置项
     * @param array $widgetOptions Select2配置
     * @param null $pCode 父级ID
     * @return \yii\widgets\ActiveField
     */
    public function fieldAreaDepDrop($attribute = 'city', $options = [], $widgetOptions = [], $pCode = null)
    {
        $options = ArrayHelper::merge([
            'class' => 'abiao\Order\Widgets\ActiveField',
            'template' => '{input}{error}',
            'options' => ['class' => 'col-md-4 no-padding'],
        ], $options);
        $data = empty($pCode) ? [] : Area::getChildList($pCode);
        $widgetOptions = ArrayHelper::merge([
            'data' => $data,
            'options' => [],
            'pluginOptions' => [
                'depends' => ['province'],
                'url' => Url::to(['/Index/index/site']),
                'placeholder' => '请选择城市',
            ]
        ], $widgetOptions);
        return $this->fieldDepDrop($attribute, $options, $widgetOptions);
    }

    /**
     * 依赖下拉框
     * @param string $attribute model字段
     * @param array $data 下拉框数据
     * @param array $options 配置项
     * @param bool $label 是否显示label
     * @return \yii\widgets\ActiveField
     */
    public function fieldDepDrop($attribute, $options = [], $widgetOptions = [], $label = false)
    {
        $widgetOptions = ArrayHelper::merge([
            'type' => DepDrop::TYPE_SELECT2,
            'data' => [],
            'options' => [],
            'select2Options' => ['pluginOptions' => ['allowClear' => true]],
            'pluginOptions' => []
        ], $widgetOptions);
        $model = isset($options['model']) && !empty($options['model']) ? $options['model'] : $this->detailModel;
        return $this->field($model, $attribute, $options)->widget(DepDrop::classname(), $widgetOptions)->label($label);
    }

    /**
     * 大文本框输出
     * @param string $attribute model字段
     * @param array $options 配置项
     * @return $this
     */
    public function fieldTextArea($attribute, $options = [])
    {
        return $this->outPut($this->field($this->detailModel, $attribute)->textarea($options));
    }

    /**
     * 颜色选择文本框输出
     * @param string $attribute model字段
     * @param array $options 配置项
     * @param array $widgetOptions 挂件配置项
     * @return \yii\widgets\ActiveField
     */
    public function fieldColor($attribute, $options = [], $widgetOptions = [])
    {
        $widgetOptions = ArrayHelper::merge([
        ], $widgetOptions);
        return parent::field($this->detailModel, $attribute, $options)->widget(ColorInput::className(), $widgetOptions);
    }

    /**
     * 统一输出
     * @param $html
     * @return mixed
     */
    public function outPut($html)
    {
        return $html;
    }
}