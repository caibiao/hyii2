<?php
/**
 * @copyright 本软件和相关文档仅限 安乐窝 和/或其附属公司开发团队内部交流使用，
 *            并受知识产权法的保护。除非公司以适用法律明确授权，否则不得以任何形
 *            式、任何方式使用、拷贝、复制、翻译、广播、修改、授权、传播、分发、
 *            展示、执行、发布或显示本软件和相关文档的任何部分。
 * @author    林猛锋<linmengfeng@anlewo.com>
 * @date      2018/3/14 15:41
 */

namespace abiao\Order\Widgets;


use Common\helpers\Url;
use yii\helpers\ArrayHelper;

class DepDrop extends \kartik\depdrop\DepDrop
{
    /**
     * 地区依赖下拉框
     * @param $options
     * @param $pCode
     * @param $depends
     * @return string
     */
    public static function fieldArea($options, $pCode, $depends)
    {
        try {
            $data = empty($pCode) ? [] : \abiao\Service\System\Area::getChildList($pCode);
            $options = ArrayHelper::merge([
                'type' => DepDrop::TYPE_SELECT2,
                'data' => $data,
                'options' => [],
                'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                'pluginOptions' => [
                    'depends' => [$depends],
                    'url' => Url::to(['/Index/index/site']),
                    'placeholder' => '请选择',
                ]
            ], $options);
            return DepDrop::widget($options);
        } catch (\Exception $e) {
            return '';
        }
    }
}