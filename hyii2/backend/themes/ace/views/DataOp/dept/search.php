<?php
/**
 * @copyright 本软件和相关文档仅限 安乐窝 和/或其附属公司开发团队内部交流使用，
 *            并受知识产权法的保护。除非公司以适用法律明确授权，否则不得以任何形
 *            式、任何方式使用、拷贝、复制、翻译、广播、修改、授权、传播、分发、
 *            展示、执行、发布或显示本软件和相关文档的任何部分。
 * @author    林猛锋<linmengfeng@anlewo.com>
 * @date      2018/3/14 17:10
 */
use abiao\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $searchModel Anlewo\Service\Store\Models\BigCustom\Search */

?>
<style>
    .search-city-box {
        width: 400px;
        float: left;
    }
    .search-city-box label{
        padding-top: 7px;
        margin-bottom: 0px;
        text-align: right;
    }
</style>
<div class="box-body">
    <?php
    $form = ActiveForm::begin([
        'method' => 'get',
        'searchModel' => $searchModel,
        'action' => Url::toRoute(['index'])
    ]);
    ?>
    <div class="row">
        <?= $form->fieldText('no') ?>
        <?= $form->fieldText('name') ?>
    </div>

    <div class="row">
        <div class="col-md-2">
            <?= $form->buttonSubmit('/Store/big-custom/index') ?>
            <?= $form->buttonReset('/Store/big-custom/index') ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>