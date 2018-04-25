<?php
/**
 * @copyright 本软件和相关文档仅限 安乐窝 和/或其附属公司开发团队内部交流使用，
 *            并受知识产权法的保护。除非公司以适用法律明确授权，否则不得以任何形
 *            式、任何方式使用、拷贝、复制、翻译、广播、修改、授权、传播、分发、
 *            展示、执行、发布或显示本软件和相关文档的任何部分。
 * @author    林猛锋<linmengfeng@anlewo.com>
 * @date      2018/3/12 23:24
 */

use yii\helpers\Url;
use Anlewo\Order\Widgets\DetailActiveForm;

/* @var $isEdit boolean */
/* @var $model Anlewo\Service\Store\Models\BigCustom\View */
/* @var $contactModels array */
/* @var $addressModels array */

$this->title = $model->isNewRecord ? '新增大客户' : ($isEdit ? '大客户编辑' : '大客户查看');
$this->registerJs($this->render('js/view.js'), yii\web\View::POS_END);
if ($model->isNewRecord) {
    $js = <<<JS
    // 文档加载完成后设置城市下拉框为可用
    window.onload=function(){
        $('#bigcustom-area').prop("disabled", false);
    }
JS;
    $this->registerJs($js, yii\web\View::POS_END);
}
?>
<?php
$form = DetailActiveForm::begin([
    'detailModel' => $model,
    'id' => 'bigCustomForm',
    'staticOnly' => !$isEdit,
]);
?>
<section class="content-header">
    <h1>
        <?= $this->title ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= Url::toRoute(['index']) ?>">大客户管理</a></li>
        <li class="active"><a href="#"><?= $this->title ?></a></li>
    </ol>
</section>
<?= $this->render('button', [
    'isEdit' => $isEdit,
    'model' => $model,
]); ?>

<!-- Main content -->
<section class="content">
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">大客户详情</h3>
        </div>
        <div class="box box-solid">
            <div class="box-body no-padding">
                <table class="table table-bordered">
                    <tbody>
                    <?= $this->render($isEdit ? 'edit-main' : 'view-main', ['form' => $form]) ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="box box-default">
        <div class="box-header">
            <h3 class="box-title">订单联系人</h3>
        </div>
        <div class="box-body no-padding">
            <?= $this->render('contact-list', ['form' => $form, 'contactModels' => $contactModels]) ?>
        </div>
    </div>
    <div class="box box-default">
        <div class="box-header">
            <h3 class="box-title">收/提货信息</h3>
        </div>
        <div class="box-body no-padding">
            <?= $this->render('address-list', ['form' => $form, 'addressModels' => $addressModels]) ?>
        </div>
    </div>
</section>
<?php DetailActiveForm::end() ?>


