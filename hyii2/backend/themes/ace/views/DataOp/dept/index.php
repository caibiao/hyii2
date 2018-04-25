<?php
/**
 * 列表页
 * @copyright 本软件和相关文档仅限 安乐窝 和/或其附属公司开发团队内部交流使用，
 *            并受知识产权法的保护。除非公司以适用法律明确授权，否则不得以任何形
 *            式、任何方式使用、拷贝、复制、翻译、广播、修改、授权、传播、分发、
 *            展示、执行、发布或显示本软件和相关文档的任何部分。
 * @author    林猛锋<linmengfeng@anlewo.com>
 */

$this->title = '大客户管理-大客户列表';

?>
<!-- Content Header (Page header) -->
<section class="content-header search-content-header">
    <h1>
        部门管理
        <span class="btnGroupBox">
                <?= \abiao\widgets\Atag::addButton() ?>
        </span>
    </h1>
    <ol class="breadcrumb">
        <li>部门管理</li>
        <li class="active"><a href="">部门列表</a></li>
    </ol>
</section>
<section class="content">
    <div class="box search-box" id="searchBox">
        <!-- 搜索表单开始 -->
        <?= $this->render("search", [
            'searchModel' => $searchModel,
        ]); ?>
    </div>
    <div class="box box-solid no-mb">
        <!-- 搜索表单开始 -->
        <?= $this->render("list", [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]); ?>
    </div>
</section>