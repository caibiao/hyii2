<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model Service\DataOp\Models\Search */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dept-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'no') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'name_en') ?>

    <?= $form->field($model, 'name_ru') ?>

    <?php // echo $form->field($model, 'name_big') ?>

    <?php // echo $form->field($model, 'parent_no') ?>

    <?php // echo $form->field($model, 'country') ?>

    <?php // echo $form->field($model, 'area') ?>

    <?php // echo $form->field($model, 'inner_no') ?>

    <?php // echo $form->field($model, 'role') ?>

    <?php // echo $form->field($model, 'datetime') ?>

    <?php // echo $form->field($model, 'service_proportion') ?>

    <?php // echo $form->field($model, 'consume_proportion') ?>

    <?php // echo $form->field($model, 'coins_web') ?>

    <?php // echo $form->field($model, 'coins') ?>

    <?php // echo $form->field($model, 'sms_nums') ?>

    <?php // echo $form->field($model, 'bank') ?>

    <?php // echo $form->field($model, 'account') ?>

    <?php // echo $form->field($model, 'account_name') ?>

    <?php // echo $form->field($model, 'mobile') ?>

    <?php // echo $form->field($model, 'tel') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'postalcode') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'stock') ?>

    <?php // echo $form->field($model, 'order_credit') ?>

    <?php // echo $form->field($model, 'goods_credit') ?>

    <?php // echo $form->field($model, 'award_flag') ?>

    <?php // echo $form->field($model, 'discount') ?>

    <?php // echo $form->field($model, 'currency_id') ?>

    <?php // echo $form->field($model, 'flag') ?>

    <?php // echo $form->field($model, 'has_coins') ?>

    <?php // echo $form->field($model, 'user_no') ?>

    <?php // echo $form->field($model, 'intro_no') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
