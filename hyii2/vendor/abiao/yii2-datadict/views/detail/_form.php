<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use abiao\datadict\models\DatadictDetail;

$this->registerJs($this->render("../datadict/js/form.js"));
?>

<div class="add-detail-form row">

    <?php $form = ActiveForm::begin([
        'id' => 'frm-datadict-detail',
        'fullSpan' => 10,
        'formConfig' => [
            // 'labelSpan' => 3
        ],
    ]); ?>
        <div class="form-group">
            <div class="col-md-12">
                <?= $form->field($model, 'fid')->widget(Select2::className(), [
                    'data' => ArrayHelper::map(
                        DatadictDetail::find()
                            ->where(['pid' => $pid, 'fid' => null])
                            ->orderBy(['orders' => 'asc'])
                            ->all(),
                        'id',
                        'name'
                    ),
                    'options' => ['placeholder' => '无父级'],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]) ?>
            </div>
            <div class="col-md-12">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-12">
                <?= $form->field($model, 'orders')->textInput() ?>
            </div>
            <div class="col-md-12">
                <?= $form->field($model, 'remark')->textInput() ?>
            </div>
            <div class="col-md-12">
                <?= Html::submitButton($model->isNewRecord ? '新增' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>

</div>
