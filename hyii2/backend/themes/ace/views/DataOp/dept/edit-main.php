<?php
/**
 * 大客户主要信息编辑模板
 * @copyright 本软件和相关文档仅限 安乐窝 和/或其附属公司开发团队内部交流使用，
 *            并受知识产权法的保护。除非公司以适用法律明确授权，否则不得以任何形
 *            式、任何方式使用、拷贝、复制、翻译、广播、修改、授权、传播、分发、
 *            展示、执行、发布或显示本软件和相关文档的任何部分。
 * @author    林猛锋<linmengfeng@anlewo.com>
 * @date      2018/3/13 11:26
 */

/* @var $form \Anlewo\Order\Widgets\DetailActiveForm */
?>

<tr>
    <td><?= $form->fieldCode('code') ?></td>
    <td><?= $form->fieldInput('name') ?></td>
    <td><?= $form->fieldDropDown('category', \Anlewo\Service\Store\BigCustom::getCategoryList()) ?></td>
</tr>
<tr>
    <td>
        <div class="form-group required">
            <label class="control-label col-md-3">注册地区</label>
            <div class="col-md-9">
                <?= $form->fieldProvince('province', [], ['disabled' => !$form->detailModel->isNewRecord, 'options' => [
                    'placeholder' => '请选择省份',
                    'id' => 'province',
                ]]) ?>
                <?= $form->fieldAreaDepDrop('area', [], ['disabled' => !$form->detailModel->isNewRecord], $form->detailModel->province) ?>
                <div style="display: none;">
                    <?= $form->fieldAreaDepDrop('areaHide', [], ['pluginOptions' => [
                        'depends' => ['bigcustom-area'],
                        'url' => \yii\helpers\Url::to(['/Index/index/site']),
                    ]], $form->detailModel->area) ?>
                </div>
            </div>
        </div>
    </td>
    <td><?= $form->fieldSelect2('tagIdArray', [], [
            'data' => \Anlewo\Service\System\Tag::getBigCustomList(),
            'options' => [
                'multiple' => true,
                'placeholder' => '请选择',
            ]
        ]) ?></td>
    <td><?= $form->fieldDropDown('businessState', \Anlewo\Service\Store\BigCustom::getStateList()) ?></td>
</tr>
<tr>
    <td><?= $form->fieldStatic('creater') ?></td>
    <td><?= $form->fieldStaticDateTime('created') ?></td>
    <td><?= $form->fieldTextArea('remark') ?></td>
</tr>
