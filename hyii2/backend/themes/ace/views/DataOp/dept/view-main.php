<?php
/**
 * @copyright 本软件和相关文档仅限 安乐窝 和/或其附属公司开发团队内部交流使用，
 *            并受知识产权法的保护。除非公司以适用法律明确授权，否则不得以任何形
 *            式、任何方式使用、拷贝、复制、翻译、广播、修改、授权、传播、分发、
 *            展示、执行、发布或显示本软件和相关文档的任何部分。
 * @author    林猛锋<linmengfeng@anlewo.com>
 * @date      2018/3/14 11:47
 *
 */
/* @var $form \Anlewo\Order\Widgets\DetailActiveForm */
?>
<tr>
    <td><?= $form->fieldCode('code') ?></td>
    <td><?= $form->fieldStatic('name') ?></td>
    <td><?= $form->fieldStatic('category') ?></td>
</tr>
<tr>
    <td><?= $form->fieldStatic('areaName') ?></td>
    <td><?= $form->fieldStatic('tagName') ?></td>
    <td><?= $form->fieldStatic('stateMsg') ?></td>
</tr>
<tr>
    <td><?= $form->fieldStatic('creater') ?></td>
    <td><?= $form->fieldStaticDateTime('created') ?></td>
    <td><?= $form->fieldStatic('remark') ?></td>
</tr>
