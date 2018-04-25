<?php
/**
 * @copyright 本软件和相关文档仅限 安乐窝 和/或其附属公司开发团队内部交流使用，
 *            并受知识产权法的保护。除非公司以适用法律明确授权，否则不得以任何形
 *            式、任何方式使用、拷贝、复制、翻译、广播、修改、授权、传播、分发、
 *            展示、执行、发布或显示本软件和相关文档的任何部分。
 * @author    林猛锋<linmengfeng@anlewo.com>
 * @date      2018/3/13 09:39
 */

use anlewo\widgets\Button;
use anlewo\widgets\Atag;

/* @var $isEdit boolean */
/* @var $model Anlewo\Service\Store\Models\BigCustom\View */
?>
<div class="box-footer p10">
    <?php if ($isEdit) : ?>
        <?php
        $route = $model->isNewRecord ? ['add'] : ['edit'];
        echo Button::widget([
            'id' => 'save-form',
            'label' => '保存',
            'route' => $route,
            'options' => ['class' => 'btn btn-purple btn-w82 mr5 ', 'type' => 'submit'],
        ]);
        ?>
    <?php else : ?>
        <?= Atag::widget([
            'text' => '编辑',
            'visabled' => false,
            'route' => ['edit', 'code' => $model->code],
            'options' => ['class' => 'btn btn-purple btn-w82 mr5'],
        ]) ?>
    <?php endif; ?>
    <?php
    if (!$isEdit) {
        echo Button::widget([
            'label' => '删除',
            'route' => ['delete'],
            'options' => [
                'class' => 'btn btn-purple mr5 ajax-submit',
                'data-msg' => '确认删除该大客户吗？',
                'data-url' => \yii\helpers\Url::toRoute(['delete', 'code' => $model->code]),
                'type' => 'button'
            ],
        ]);
    }
    ?>
</div>