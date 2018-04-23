<?php
/**
 * @copyright 本软件和相关文档仅限 安乐窝 和/或其附属公司开发团队内部交流使用，
 *            并受知识产权法的保护。除非公司以适用法律明确授权，否则不得以任何形
 *            式、任何方式使用、拷贝、复制、翻译、广播、修改、授权、传播、分发、
 *            展示、执行、发布或显示本软件和相关文档的任何部分。
 * @author    林猛锋<linmengfeng@abiao.com>
 * @date      2018/3/19 23:47
 */

namespace abiao\Order\Widgets;


class ActionColumn extends \kartik\grid\ActionColumn
{
    /**
     * 删除列表
     * @param string $deleteUrl 提交路径
     * @return array
     */
    public static function deleteColumn($deleteUrl)
    {
        return [
            'class' => 'kartik\grid\ActionColumn',
            'header' => '操作',
            'template' => '{delete}',
            'buttons' => [
                'delete' => function ($url, $model) use ($deleteUrl) {
                    return '<a class="action-del" href="javascript:void(0)" title="删除" data-url="'. $deleteUrl . '" data-id="' . $model->id . '"><span class="glyphicon glyphicon-trash"></span></a>';
                }
            ]
        ];
    }
}