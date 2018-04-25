<?php
/**
 * @copyright 本软件和相关文档仅限 安乐窝 和/或其附属公司开发团队内部交流使用，
 *            并受知识产权法的保护。除非公司以适用法律明确授权，否则不得以任何形
 *            式、任何方式使用、拷贝、复制、翻译、广播、修改、授权、传播、分发、
 *            展示、执行、发布或显示本软件和相关文档的任何部分。
 * @author    林猛锋<linmengfeng@anlewo.com>
 * @date      2018/3/15 10:56
 */

namespace abiao\Widgets;


use yii\helpers\ArrayHelper;

class GridView extends \kartik\grid\GridView
{
    /**
     * 列表页
     * @param array $config 配置项
     * @return string
     */
    public static function indexWidget($config = [])
    {
        $config = ArrayHelper::merge([
            'options' => ['class' => 'grid-view'],
            'tableOptions' => ['class' => 'table table-striped table-bordered table-center table-fixed table-index-list min-w1800'],
            'layout' => '{items}<div class="box-footer clearfix js-footer pagination-box"><div class="pull-right">{pager}</div></div>',
            'toolbar' => '',
            'panel' => [
                'type' => 'default',
                'heading' => false,
                'footer' => false,
                'after' => '{pager}',
                'before' => false,
            ],
            'pager' => [
                'class' => '\abiao\widgets\LinkPager',
                'template' => '<div class="box-footer clearfix pagination-box"><div class="pull-right"><div class="form-inline">{summary}{pageButtons}</div></div></div>'
            ],
            'columns' => [],
        ], $config);
        try {
            return static::widget($config);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public static function EditableColumn($config)
    {
        return ArrayHelper::merge([
            'class' => 'kartik\grid\EditableColumn',
            'editableOptions' => [
                'size' => 'md',
                'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                'formOptions' => ['action' => ['edit']],
                'asPopover' => true,
                'placement' => \kartik\popover\PopoverX::ALIGN_BOTTOM_LEFT,
                'showButtonLabels' => true,
                'submitButton' => [
                    'icon' => '<i class="fa fa-save"></i>',
                    'label' => '保存',
                    'class' => 'btn btn-sm btn-primary',
                ],
            ],
        ], $config);
    }
}