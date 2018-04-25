<?php
/**
 * 表单
 * @copyright 本软件和相关文档仅限 安乐窝 和/或其附属公司开发团队内部交流使用，
 *            并受知识产权法的保护。除非公司以适用法律明确授权，否则不得以任何形
 *            式、任何方式使用、拷贝、复制、翻译、广播、修改、授权、传播、分发、
 *            展示、执行、发布或显示本软件和相关文档的任何部分。
 * @author    林猛锋<linmengfeng@abiao.com>
 * @date      2018/3/6 09:39
 */

namespace abiao\widgets;
use abiao\widgets\Atag;
use kartik\daterange\DateRangePicker;
use kartik\field\FieldRange;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use abiao\widgets\Button;
use yii\helpers\Url;

class ActiveForm extends \kartik\widgets\ActiveForm
{
    /**
     * @var \yii\base\Model
     */
    public $searchModel;

    public static function begin($config = [])
    {
        if (isset($config['options']['class'])) {
            $config['options']['class'] .= ' search-form';
        } else {
            $config['options']['class'] = 'search-form';
        }
        return parent::begin($config);
    }

    /**
     * 大客户、加盟商选择框
     * @param string $attribute model字段
     * @return \yii\widgets\ActiveField
     */
    public function fieldVendorCode($attribute = 'vendorCode')
    {
        return $this->fieldSelect2($attribute, AllCustom::getDropDownList());
    }

    /**
     * 大客户、加盟商选择框（多选）
     * @param string $attribute model字段
     * @return \yii\widgets\ActiveField
     */
    public function fieldVendorCodeMultiple($attribute = 'vendorCode')
    {
        return $this->fieldSelect2($attribute, AllCustom::getDropDownList(), true);
    }

    /**
     * 加盟商选择框
     * @param string $attribute model字段
     * @return \yii\widgets\ActiveField
     */
    public function fieldStore($attribute = 'vendorCode')
    {
        return $this->fieldSelect2($attribute, Store::getDropDownList());
    }

    /**
     * 加盟商选择框
     * @param string $attribute model字段
     * @return \yii\widgets\ActiveField
     */
    public function fieldStoreMultiple($attribute = 'vendorCode')
    {
        return $this->fieldSelect2($attribute, Store::getDropDownList(), true);
    }

    /**
     * 商品分类选择框
     * @param string $attribute model字段
     * @return \yii\widgets\ActiveField
     */
    public function fieldCate($attribute = 'cateId')
    {
        return $this->fieldSelect2($attribute, GoodsClass::getDropDownList());
    }

    /**
     * 商品品牌选择框
     * @param string $attribute model字段
     * @return \yii\widgets\ActiveField
     */
    public function fieldBrand($attribute = 'brandId')
    {
        return $this->fieldSelect2($attribute, Brand::getDropDownList());
    }

    /**
     * 厂家选择框
     * @param string $attribute model字段
     * @return \yii\widgets\ActiveField
     */
    public function fieldFactory($attribute = 'factoryId')
    {
        return $this->fieldSelect2($attribute, Factory::getDropDownList());
    }

    /**
     * 商品品牌多选框
     * @param string $attribute model字段
     * @return \yii\widgets\ActiveField
     */
    public function fieldBrandMultiple($attribute = 'brandId')
    {
        return $this->fieldSelect2($attribute, Brand::getDropDownList(), true);
    }

    /**
     * 订单平台用户选择框
     * @param string $attribute model字段
     * @return \yii\widgets\ActiveField
     */
    public function fieldOrderPlatFormUserList($attribute)
    {
        return $this->fieldSelect2($attribute, User::getOrderPlatformFilterList());
    }

    /**
     * 订单平台用户选择框改造
     * @param string $attribute model字段
     * @return \yii\widgets\ActiveField
     */
    public function fieldOrderPlatFormUserListChange($attribute)
    {
        $userList=User::getOrderPlatformFilterList();

        $newUserList=[];
        foreach($userList as $key => $value){
            $newUserList[$value]=$value;
        }
        return $this->fieldSelect2($attribute, $newUserList);
    }

    /**
     * Select2下拉框
     * @param string $attribute model字段
     * @param array $data 下拉选项素组
     * @param boolean $multiple 是否多选
     * @return \yii\widgets\ActiveField
     */
    public function fieldSelect2($attribute, $data = [], $multiple = false)
    {
        return $this->outPut(
            $this->field($this->searchModel, $attribute)->widget(Select2::classname(), [
                'data' => $data,
                'options' => [
                    'multiple' => $multiple,
                    'placeholder' => $this->searchModel->getAttributeLabel($attribute)
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label(false)
        );
    }

    /**
     * 普通下拉框
     * @param $attribute
     * @param array $data 下拉选项
     * @return \yii\widgets\ActiveField
     */
    public function fieldDropDown($attribute, $data)
    {
        $data = ArrayHelper::merge(['' => $this->searchModel->getAttributeLabel($attribute)], $data);
        return $this->outPut(
            $this->field($this->searchModel, $attribute, [
                'inputOptions' => [
                    'class' => 'form-control select-warp-option',
                ],
            ])->label(false)->dropDownList($data)
        );
    }

    /**
     * 订单类型
     * @return \yii\widgets\ActiveField
     */
    public function fieldOrderType()
    {
        $orderTypeList = BaseOrders::getOrderTypeList();
        $orderTypeList[\abiao\Common\Models\BaseOrders::ORDER_TYPE_F2B_IPAD] = 'F2B主材包';
        return $this->fieldDropDown('orderType', $orderTypeList);
    }

    /**
     * 仓库
     * @param string $attribute model字段
     * @return \yii\widgets\ActiveField
     */
    public function fieldStorage($attribute = 'storageId')
    {
        return $this->fieldDropDown($attribute, Storage::getDropDownList());
    }

    /**
     * 日期段选择
     * @param string $attribute model字段
     * @param array $options 配置项
     * @return string
     */
    public function fieldDateRange($attribute, $options = [])
    {
        $prefixHtml = <<< HTML
        <div class="search-group form-date">
                    <div class="input-group-addon">
                        <i class="glyphicon glyphicon-time"></i>
                    </div>
HTML;
        $style = !empty($this->searchModel->$attribute) ? 'style="display:block"' : '';
        $suffixHtml = <<< HTML
        <span class="search-control-clear" {$style}>×</span>
                </div>
HTML;
        $options = array_merge([
            'model' => $this->searchModel,
            'attribute' => $attribute,
            'convertFormat' => true,
            'options' => [
                'placeholder' => $this->searchModel->getAttributeLabel($attribute),
                'readonly' => true,
                'class' => 'form-control select-warp-option  search-hight'
            ],
            'pluginOptions' => [
                'timePicker' => false,
                'locale' => [
                    'format' => 'Y/m/d',
                    'separator' => ' - ',
                ],
            ],
        ], $options);

        try {
            return $this->outPut($prefixHtml . DateRangePicker::widget($options) . $suffixHtml);
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * 时间选择框
     * @param string $attribute model字段
     * @param array $options 配置项
     * @return \yii\widgets\ActiveField
     */
    public function fieldDatePicker($attribute, $options = [])
    {
        $options = array_merge([
            'attribute' => $attribute,
            'options' => [
                'class' => 'input-sm',
                'placeholder' => $this->searchModel->getAttributeLabel($attribute),
            ],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy/mm/dd',
                'todayBtn' => true,
                'todayHighlight' => true,
            ]
        ], $options);
        return $this->outPut(
            $this->field($this->searchModel, $attribute)
                ->widget(DatePicker::className(), $options)->label(false)
        );
    }

    /**
     * 范围输入框
     * @param string $attribute1 开始
     * @param string $attribute2 结束
     * @return string
     */
    public function fieldRange($attribute1, $attribute2)
    {
        try {
            return $this->outPut(
                FieldRange::widget([
                    'form' => $this,
                    'model' => $this->searchModel,
                    'attribute1' => $attribute1,
                    'attribute2' => $attribute2,
                    'options1' => [
                        'placeholder' => $this->searchModel->getAttributeLabel($attribute1),
                    ],
                    'options2' => [
                        'placeholder' => $this->searchModel->getAttributeLabel($attribute2),
                    ],
                    'type' => FieldRange::INPUT_TEXT,
                    'separator' => '~',
                    'template' => '{widget}{error}',
                ])
            );
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * 文字输入框
     * @param string $attribute model字段
     * @return \yii\widgets\ActiveField
     */
    public function fieldText($attribute)
    {
        return $this->outPut(
            $this->field($this->searchModel, $attribute, [
                'inputOptions' => [
                    'placeholder' => $this->searchModel->getAttributeLabel($attribute),
                    'class' => 'form-control',
                ],
            ])->label(false)
        );
    }

    /**
     * 省份下拉框
     * @param string $attribute model字段
     * @param array $options 配置项
     * @param array $widgetOptions selete2配置项
     * @return mixed
     */
    public function fieldProvince($attribute = 'province', $options = [], $widgetOptions = [])
    {
        $options = ArrayHelper::merge([
            'class' => 'abiao\Order\Widgets\ActiveField',
            'template' => '{input}{error}',
            'options' => ['class' => 'no-padding'],
        ], $options);
        $widgetOptions = ArrayHelper::merge([
            'data' =>  Area::getChildList(0),
            'options' => [
                'multiple' => false,
                'placeholder' => '请选择省份',
                'id' => $attribute,
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ], $widgetOptions);
        return $this->field($this->searchModel, $attribute, $options)->widget(Select2::classname(), $widgetOptions);
    }

    /**
     * 城市下拉框
     * @param string $attribute model字段
     * @param array $options 配置项
     * @param array $widgetOptions selete2配置项
     * @param null $pCode 父级ID
     * @return \yii\widgets\ActiveField
     */
    public function fieldCity($attribute = 'city', $options = [], $widgetOptions = [], $pCode = null)
    {
        $options = ArrayHelper::merge([
            'class' => 'abiao\Order\Widgets\ActiveField',
            'template' => '{input}{error}',
            'options' => ['class' => 'no-padding'],
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
     * 订单状态
     * @param string $attribute
     * @param array $options
     * @param array $widgetOptions
     * @param null $orderType
     * @return mixed
     */
    public function fieldOrderState($attribute = 'orderState', $options = [], $widgetOptions = [], $orderType = null, $dependsId = 'ordertype')
    {
        $data = Orders::getStateListByOrderType($orderType);
        $widgetOptions = ArrayHelper::merge([
            'type' => DepDrop::TYPE_DEFAULT,
            'data' => $data,
            'options' => ['placeholder' => '订单状态',],
            'pluginOptions' => [
                'depends' => [$dependsId],
                'url' => Url::to(['/Index/index/order-type']),
                'placeholder' => '订单状态',
            ]
        ], $widgetOptions);
        return $this->outPut($this->fieldDepDrop($attribute, $options, $widgetOptions));
    }

    /**
     * 依赖下拉框
     * @param string $attribute model字段
     * @param array $options 配置项
     * @param array $widgetOptions DepDrop配置
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
        return $this->field($this->searchModel, $attribute, $options)->widget(DepDrop::classname(), $widgetOptions)->label($label);
    }

    /**
     * 提交按钮
     * @param string $route 权限路径
     * @return string
     */
    public function buttonSubmit($route)
    {
        try {
            return Button::widget([
                'label' => '开始搜索',
                'route' => $route,
                'options' => [
                    'type' => 'submitButton',
                    'class' => 'btn btn-info mr5',
                ]]);
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * 重置按钮
     * @param string $route 权限路径
     * @return string
     */
    public function buttonReset($route)
    {
        try {
            return Atag::widget([
                'text' => '重置',
                'route' => [$route, 'type' => 'reset'],
                'options' => [
                    'class' => 'btn btn-default btn-w82',
                ]
            ]);
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * 导出按钮
     * @param string $route 权限路径
     * @return string
     */
    public function buttonExport($route)
    {
        try {
            return Button::widget([
                'label' => '导出',
                'route' => [$route],
                'options' => [
                    'class' => 'btn btn-purple mr5',
                    'type' => 'button',
                    'id' => 'btn-export',
                ]
            ]);
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * 输出
     * @param $output
     * @return mixed
     */
    public function outPut($output)
    {
        $prefixHtml = <<< HTML
       <div class="col-md-2">
            <div class="form-group">
HTML;
        $suffixHtml = <<< HTML
       </div>
        </div>
HTML;
        return $prefixHtml . $output . $suffixHtml;
    }
}