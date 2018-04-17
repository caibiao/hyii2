<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;

$this->registerJs($this->render("js/list.js"));
?>

<div class="box box-solid">
    <?= GridView::widget([
        'id' => "sub-{$pid}",
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'toolbar' => '',
        'panel' => [
            'type' => 'primary',
            'heading' => false,
            'after' => false,
            'footer' => false,
            'before' => Html::a('<i class="glyphicon glyphicon-plus"></i> 新增明细', ['add', 'pid' => $pid], ['class' => 'btn btn-success detail-add-button', 'data-pjax' => 0]),
        ],
        'columns' => [
            [
                'attribute' => 'id',
            ],
            [
                'attribute' => 'name',
            ],
            [
                'header' => '父级',
                'attribute' => 'father.name',
            ],
            [
                'attribute' => 'remark',
            ],
            [
                'attribute' => 'orders',
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'width' => '120px',
                'buttons'=>[
                    'view' => function ($url, $model) {
                        return '';
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span> 编辑', $url, ['class' => 'btn-xs btn-primary update-button pull-left']);
                    },
                    'delete'=>function ($url, $model) {

                        $url = Url::to(['enabled', 'id' => $model->id]);
                        $dispaly = $model->flag==1 ? 'inline': 'none';
                        $enabled = Html::a('<span class="glyphicon glyphicon-ban-circle"></span> 启用', $url, [
                            'class' => 'btn-xs btn-success enabled-button pull-right',
                            'style' => 'display: ' . $dispaly
                        ]);

                        $dispaly = $model->flag==0 ? 'inline': 'none';
                        $url = Url::to(['disabled', 'id' => $model->id]);
                        $disabled = Html::a('<span class="glyphicon glyphicon-ban-circle"></span> 停用', $url, [
                            'class' => 'btn-xs btn-danger disabled-button pull-right',
                            'style' => 'display: ' . $dispaly
                        ]);

                        return $enabled . $disabled;
                    },
                ],
            ],
        ]
    ]); ?>
</div>


<?php Modal::begin([
  'id' => 'detail-add-modal',
  'size' => 'modal-md',
  'header' => '<h4>字段明细设置</h4>',
  'clientOptions' => ['backdrop' => 'static', 'keyboard' => false]
])?>
<div id='detail-add-modal-view'></div>
<?php Modal::end() ?>
