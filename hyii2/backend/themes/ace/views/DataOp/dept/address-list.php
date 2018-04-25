<?php
/**
 * @copyright 本软件和相关文档仅限 安乐窝 和/或其附属公司开发团队内部交流使用，
 *            并受知识产权法的保护。除非公司以适用法律明确授权，否则不得以任何形
 *            式、任何方式使用、拷贝、复制、翻译、广播、修改、授权、传播、分发、
 *            展示、执行、发布或显示本软件和相关文档的任何部分。
 * @author    林猛锋<linmengfeng@anlewo.com>
 * @date      2018/3/14 14:46
 */

/* @var $form \Anlewo\Order\Widgets\DetailActiveForm */
/* @var $addressModels array */

use yii\helpers\Html;
use yii\helpers\Url;
use Anlewo\Order\Widgets\DynamicForm\DynamicFormWidget;
use Anlewo\Order\Widgets\DetailActiveForm;

DynamicFormWidget::begin([
    'widgetContainer' => 'address_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
    'widgetBody' => '.container-items-address', // required: css class selector
    'widgetItem' => '.item-address', // required: css class
    'min' => 0, // 0 or 1 (default 1)
    'insertButton' => '.add-item', // css class
    'deleteButton' => '.remove-item', // css class
    'model' => $addressModels[0],
    'formId' => 'bigCustomForm',
    'formFields' => [
        'name',
        'position',
        'phone',
        'remark',
    ],
]); ?>
    <table class="table table-striped table-bordered table-center table-fixed kv-grid-table table-hover kv-table-wrap">
        <thead>
        <tr>
            <th class="kv-align-top required" width="400px">省市区/县</th>
            <th class="kv-align-top">详细地址</th>
            <th class="kv-align-top required">收/提货人</th>
            <th class="kv-align-top required">电话</th>
            <th class="kv-align-top">身份证号</th>
            <th class="kv-align-top">备注</th>
            <th class="kv-align-center kv-align-middle skip-export" width="100px">操作</th>
        </tr>
        </thead>
        <tbody class="container-items-address">
        <?php if (!empty($addressModels)): ?>
            <?php foreach ($addressModels as $index => $addressModel): ?>
                <tr class="item-address">
                    <td>
                        <?php
                        if (!$addressModel->isNewRecord) {
                            echo Html::activeHiddenInput($addressModel, "[{$index}]id");
                        }
                        ?>
                        <?php if ($form->staticOnly) : ?>
                            <?=$addressModel->areaInfo?>
                        <?php else : ?>
                            <?= $form->fieldProvince("[{$index}]province", ['model' => $addressModel], ['options' => [
                                'placeholder' => '请选择省份',
                            ]]) ?>
                            <?= $form->fieldAreaDepDrop("[{$index}]city", ['model' => $addressModel],
                                ['pluginOptions' => [
                                    'depends' => ['storedeliveryaddress-' . $index . '-province'],
                                    'url' => Url::to(['/Index/index/site']),
                                    'placeholder' => '请选择城市',
                                ]], $addressModel->province) ?>
                            <?= $form->fieldAreaDepDrop("[{$index}]area", ['model' => $addressModel],
                                ['pluginOptions' => [
                                    'depends' => ['storedeliveryaddress-' . $index . '-city'],
                                    'url' => Url::to(['/Index/index/site']),
                                    'placeholder' => '请选择地区',
                                ]], $addressModel->city) ?>
                        <?php endif;?>
                    </td>
                    <td><?= $form->field($addressModel, "[{$index}]address", DetailActiveForm::notLabelOptions()) ?></td>
                    <td><?= $form->field($addressModel, "[{$index}]name", DetailActiveForm::notLabelOptions()) ?></td>
                    <td><?= $form->field($addressModel, "[{$index}]phone", DetailActiveForm::notLabelOptions()) ?></td>
                    <td><?= $form->field($addressModel, "[{$index}]cardId", DetailActiveForm::notLabelOptions()) ?></td>
                    <td><?= $form->field($addressModel, "[{$index}]remark", DetailActiveForm::notLabelOptions()) ?></td>
                    <td class="skip-export kv-align-center kv-align-middle">
                        <?php if (!$form->staticOnly) : ?>
                            <a href="javascript:void(0);" class="remove-item"><i class="glyphicon glyphicon-trash"></i></a>
                        <?php endif;?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
<?php if (!$form->staticOnly) : ?>
    <div class="box-footer p10">
        <a class="btn btn-success add-item" href="javascript:void(0);"><i class="glyphicon glyphicon-plus"></i> 新 增 </a>
    </div>
<?php endif; ?>
<?php DynamicFormWidget::end(); ?>