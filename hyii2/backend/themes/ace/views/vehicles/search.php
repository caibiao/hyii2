<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use kartik\daterange\DateRangePicker;
use Anlewo\Common\Models\BaseOrders;
use kartik\select2\Select2;
use kartik\date\DatePicker;


$site = Url::toRoute(['site']);
$actionId = Yii::$app->controller->action->id;
$addUrl = Url::toRoute(['add-factory']);



$js = <<<JS


JS;
$this->registerJs($js);
?>


    <?php
    $form = ActiveForm::begin([
        'id' => 'searchModel',
        'method' => 'get',
        'action' => Url::toRoute([$actionId])
    ]);
    ?>
<div class="box-body">
    <div class="row">
        <div class="col-md-2">
            <?php
                echo $form->field($searchModel, 'plateNum', [
                    'inputOptions' => [
                        'placeholder' => $searchModel->getAttributeLabel('plateNum'),
                        'class' => 'form-control select-warp-option',
                    ],
                ])->label(false);
            ?>
        </div>
        <div class="col-md-2">
            <?php
                echo $form->field($searchModel, 'ton', [
                    'inputOptions' => [
                        'placeholder' => $searchModel->getAttributeLabel('ton'),
                        'class' => 'form-control select-warp-option',
                    ],
                ])->label(false);
            ?>
        </div>
        <div class="col-md-2">
            <?php
                echo $form->field($searchModel, 'userName', [
                    'inputOptions' => [
                        'placeholder' => $searchModel->getAttributeLabel('userName'),
                        'class' => 'form-control select-warp-option',
                    ],
                ])->label(false);
            ?>
        </div>
        <div class="col-md-2">
            <?php
                echo $form->field($searchModel, 'mobile', [
                    'inputOptions' => [
                        'placeholder' => $searchModel->getAttributeLabel('mobile'),
                        'class' => 'form-control select-warp-option',
                    ],
                ])->label(false);
            ?>
        </div>
        <div class="col-md-2">
            <?php
                echo $form->field($searchModel, 'idCard', [
                    'inputOptions' => [
                        'placeholder' => $searchModel->getAttributeLabel('idCard'),
                        'class' => 'form-control select-warp-option',
                    ],
                ])->label(false);
            ?>
        </div>
    </div>
</div>
<div class="box-footer p10">
        <?= Html::submitButton('开始搜索', ['class' => 'btn btn-info mr5']) ?>
        <?= Html::a('重置',[Url::to(['index', 'type'=> 'reset'])], ['class' => 'btn btn-default btn-w82']) ?>
<?php ActiveForm::end(); ?>
</div>

