<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;

$this->registerJs($this->render("js/form.js"));
?>

<div class="add-form row">

    <?php $form = ActiveForm::begin([
        'id' => 'frm-datadict',
        'fullSpan' => 10,
        'formConfig' => [
            // 'labelSpan' => 3
        ],
    ]); ?>

        <div class="form-group">
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
