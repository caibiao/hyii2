<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model Service\DataOp\Models\Dept */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dept-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name_en')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name_ru')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name_big')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'parent_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'country')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'area')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'inner_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'role')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'datetime')->textInput() ?>

    <?= $form->field($model, 'service_proportion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'consume_proportion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'coins_web')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'coins')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sms_nums')->textInput() ?>

    <?= $form->field($model, 'bank')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'account')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'account_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'postalcode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'stock')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_credit')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'goods_credit')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'award_flag')->textInput() ?>

    <?= $form->field($model, 'discount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'currency_id')->textInput() ?>

    <?= $form->field($model, 'flag')->textInput() ?>

    <?= $form->field($model, 'has_coins')->textInput() ?>

    <?= $form->field($model, 'user_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'intro_no')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
