<?php
/**
 * @Link http://www.anlewo.com/
 * @Copyright Copyright (c) 2015 Anlewo Ltd
 * @License 广东安乐窝网络科技有限公司
 */
use abiao\widgets\Button;
use abiao\widgets\Atag;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\helpers\Json;
use \mdm\admin\components\Helper as Auth;


$request = Yii::$app->request;
$this->title = '车辆管理';

$opts = Json::htmlEncode([
    'addVehiclesUrl' => Url::to(['/Vehicles/vehicles/add-vehicles', 'actionId' => 'edit']),
    'exportVehiclesUrl' => Url::to(['/Vehicles/vehicles/export-detail-data']),
    'delUrl' => Url::toRoute(['del']),
]);

$this->registerJs("var _opts = {$opts};");
$js = <<<JS
  searchBox({box: "#searchBox"});

  $(".table.table-fixed").FixedHead({
    bgColor:"#fff",
    bottomBox:".pagination-box",
    adjustHeight:"2",
    minHeight:"300"
  });

JS;
$this->registerJs($js);
$this->registerJs($this->render('js/list.js'));
?>
<section class="content-header">
    <h1>
        车辆管理 <span
                class="btnGroupBox">
                <?= Button::widget([
                    'id' => 'addVehicles',
                    'label' => '新增',
                    'route' => ['/Vehicles/vehicles/add-vehicles', 'actionId' => 'edit'],
                    'options' => ['class' => 'btn btn-info btn-w82 ml5'],
                ]) ?>
                <?= Button::widget([
                    'label' => '删除',
                    'route' => ['del'],
                    'options' => [
                        'class' => 'btn btn-info mr5',
                        'id' => 'vehiclesDel',
                        'data-url' => Url::toRoute('del')
                    ],
                ]) ?>
                <?= Button::widget([
                    'id' => 'exportVehicles',
                    'label' => '导出',
                    'route' => ['/Vehicles/vehicles/export-detail-data'],
                    'options' => ['class' => 'btn btn-info btn-w82 ml5'],
                ]) ?>
                <form id="importForm" method="post" enctype="multipart/form-data" action="<?= Yii::$app->urlManager->createUrl(['Vehicles/vehicles/import']) ?>">
                    <input type="hidden" name="_csrf" id="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>">
                    <?= Button::widget([
                        'label' => '价格导入',
                        'route' => '/Vehicles/vehicles/import',
                        'options' => [
                            'id' => 'importBtn',
                            'type' => 'button',
                            'class' => 'btn btn-info mr10',
                        ],
                    ]) ?>
                    <?php
                    if (Auth::checkRoute('/Vehicles/vehicles/import')) {
                        echo '<label class="priceInput-file">选择上传文件<input class="form-control" type="file" id="myFile" name="myFile" /></label>';
                    }
                    ?>
                </form>
            </span>
    </h1>
    <ol class="breadcrumb">
        <li>车辆管理</li>
        <li class="active">车辆管理</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="box search-box" id="searchBox">
        <!-- 搜索表单开始 -->
        <?= $this->render("search", [
            'searchModel' => $searchModel,
        ]); ?>
    </div>
    <div class="box box-solid no-mb">
        <div class="box-body no-padding">
            <?= GridView::widget([
                'id' => 'GridViewArea',
                'dataProvider' => $dataProvider,
                'pager' => [
                    'class' => '\abiao\widgets\LinkPager',
                    'template' => '<div class="box-footer clearfix pagination-box"><div class="pull-right"><div class="form-inline">{summary}{pageButtons}</div></div></div>'
                ],
                'panel' => [
                    'type' => '',
                    'heading' => false,
                    'footer' => false,
                    'before' => '',
                    'after' => '{pager}',
                ],
                'export' => false,
                'toggleData' => false,
                'columns' => [
                    [
                        'class' => 'yii\grid\CheckboxColumn',
                        'headerOptions' => ['class' => 'check-id th-w30'],
                        'name' => 'checkbox',
                    ],
                    [
                        'attribute' => 'id',
                        'headerOptions' => ['class' => 'th-w100'],
                        'header' => 'ID',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Atag::widget([
                                'text' => $model->plateNum,
                                'route' => ['info', 'id' => $model->id],
                                'options' => [
                                    'target' => '_blank',
                                ]
                            ]);
                        },
                    ],
                    [
                        'attribute' => 'userName',
                        'headerOptions' => ['class' => 'th-w150'],
                        'header' => '姓名',
                        'format' => 'raw',
                        'filterInputOptions' => ['class' => 'form-control', 'placeholder' => '支持模糊查询'],
                        'value' => function ($model) {
                            return $model->userName;
                        },
                    ],
                    [
                        'attribute' => 'mobile',
                        'headerOptions' => ['class' => 'th-w150'],
                        'header' => '手机号码',
                        'format' => 'raw',
                        'filterInputOptions' => ['class' => 'form-control', 'placeholder' => '支持模糊查询'],
                        'value' => function ($model) {
                            return $model->mobile;
                        },
                    ],
                    [
                        'attribute' => 'idCard',
                        'headerOptions' => ['class' => 'th-w150'],
                        'header' => '身份证号',
                        'format' => 'raw',
                        'filterInputOptions' => ['class' => 'form-control', 'placeholder' => '支持模糊查询'],
                        'value' => function ($model) {
                            return $model->idCard;
                        },
                    ],

                    [
                        'header' => '吨位',
                        'headerOptions' => ['class' => 'th-w150'],
                        'format' => 'raw',
                        'value' => function ($model) {
                            return $model->ton;
                        },
                    ],
                ],
                'filterModel' => $searchModel,
            ]) ?>
        </div><!-- /.box-body -->
        <!--
        <div class="box-footer clearfix pagination-box">
            <div style="float:right">
                <?=  abiao\widgets\LinkPager::widget([
                    'pagination' => $pagination,
                ]); ?>
            </div>
        </div>-->
    </div><!-- /.box -->
</section><!-- /.content -->
<style>
    .mr10 {
        margin-left: 460px;
        margin-top: -100px;
    }


</style>