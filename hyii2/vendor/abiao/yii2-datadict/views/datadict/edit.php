<?php
/**
 * @Link http://www.anlewo.com/
 * @Copyright Copyright (c) 2015 Anlewo Ltd
 * @License 广东安乐窝网络科技有限公司
 * @Author caibiao
 * @Date 2017年10月27日09:01:33
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use abiao\widgets\Atag;
use kartik\select2\Select2;

$title =  '字段管理' ;

$this->title = '字段管理-' . $title . '编辑';

?>
    <style type="text/css">
        .content-wrapper {
            margin-bottom: -20px;
        }

        .role-box {
            height: 453px;
        }

        .role-box .wrapper {
            background-color: #fff;
        }

        .role-box .content-wrapper {
            background-color: #fff;
        }

        .role-box .assignment-index {
            margin-left: -50px;
            margin-top: -50px;
        }

        .role-box .assignment-index h1 {
            display: none;
        }
    </style>
    <section class="content-header">
        <h1>
            <?= $title ?>编辑
        </h1>
        <ol class="breadcrumb">
            <li>字段</li>
            <li><a href="<?= Url::toRoute(['index']) ?>"><?= $title ?></a></li>
            <li class="active"><?= $title ?>编辑</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">基本信息</h3>
            </div>
            <?php $form = ActiveForm::begin([
                'class' => 'form-horizontal',
                'method' => 'post',
                'validateOnBlur' => true,//关闭失去焦点验证
                'enableAjaxValidation' => true, //开启Ajax验证
                'enableClientValidation' => false //关闭客户端验证
            ]) ?>
            <div class="box-body">
                <div class="col-lg-9">
                    <div class="row">
                        <label class="col-md-2 control-label"><span class="start-icon">*</span>字段名称：</label>
                        <div class="col-md-5">
                            <div class="form-group">
                                <?= $form->field($model, 'name', [
                                    'inputOptions' => [
                                        'class' => 'form-control',
                                        'placeholder' => '字段名称'
                                    ],
                                ])->label(false)
                                ?>
                            </div>
                        </div>
                        <span></span>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">备注：</label>
                        <div class="col-md-5">
                            <div class="form-group">
                                <?= $form->field($model, 'remark', [
                                    'inputOptions' => [
                                        'class' => 'form-control',
                                        'placeholder' => '备注'
                                    ],
                                ])->label(false)
                                ?>
                            </div>
                        </div>
                        <span></span>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">排序：</label>

                        <div class="col-md-5">
                            <div class="form-group">
                                <?= $form->field($model, 'orders', [
                                    'inputOptions' => [
                                        'class' => 'form-control',
                                        'placeholder' => '排序'
                                    ],
                                ])->label(false)
                                ?>
                            </div>
                        </div>
                        <span></span>
                    </div>

                </div>
            </div>
            <script id="role2" type="text/html">

            </script>
            <div class="box-footer p10">
                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-md-4 col-md-offset-2">
                            <?= Html::submitInput('提交', ['class' => ['btn btn-info btn-w82 mr5']]) ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php ActiveForm::end() ?>
        </div>
        <!-- /.box-body -->

        <!-- Your Page Content Here -->
    </section><!-- /.content -->
