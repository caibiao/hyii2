<?php
/**
 * @Link http://www.anlewo.com/
 * @Copyright Copyright (c) 2015 Anlewo Ltd
 * @License 广东安乐窝网络科技有限公司
 * @Author caibiao
 * @Date 2017年10月27日09:01:33
 */

use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\daterange\DateRangePicker;
use yii\web\JsExpression;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;

$this->title = $title = '字段管理';
$this->registerJs($this->render("js/index.js"));
?>

<section class="content-header">
    <h1><?= $title ?></h1>
    <ol class="breadcrumb">
        <li class="active"><?= $title ?></li>
    </ol>
</section>

<section class="content">
    <div class="box box-solid">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            // 'filterModel' => $model,
            'toolbar' => '',
            'panel' => [
                'type' => 'info',
                'heading' => false,
                'after' => false,
                'footer' => false,
                'before' => Html::a('<i class="glyphicon glyphicon-plus"></i> 新增', ['add'], ['class' => 'btn btn-success', 'data-pjax' => 0, 'id' => 'add-button']),
            ],
            'columns' => [
                [
                    'class' => 'kartik\grid\ExpandRowColumn',
                    'value' => function ($model, $key, $index, $column) {
                        return GridView::ROW_COLLAPSED;
                    },
                    'detailUrl' => Url::to(['detail/list']),
                    'enableRowClick' => false,
                    // 'onDetailLoaded' => new JsExpression('function(params) { $("[data-toggle=\'popover-x\']").popoverButton(); }')
                ],
                [
                    'attribute' => 'id',
                ],
                [
                    'attribute' => 'name',
                ],
                [
                    'attribute' => 'remark',
                ],
                [
                    'attribute' => 'orders',
                ],
                [
                    'attribute' => 'datetime',
                    'format' => 'datetime',
                ],
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'headerOptions' => ['class'=>'kartik-sheet-style'],
                    // 'dropdown' => true,
                    // 'dropdownOptions' => ['class' => 'pull-right'],
                    'buttons'=>[
                        'view' => function ($url, $model) {
                            return '';
                        },
                        'delete'=>function ($url, $model) {
                            return '';
                        },
                        'update' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['class' => 'update-button']);
                        },
                    ],
                ],
            ]
        ]); ?>
    </div>
</section>

<?php Modal::begin([
  'id' => 'datadict-add-modal',
  'size' => 'modal-md',
  'header' => '<h4>字段设置</h4>',
  'clientOptions' => ['backdrop' => 'static', 'keyboard' => false]
])?>
<div id='datadict-add-modal-view'></div>
<?php Modal::end() ?>
