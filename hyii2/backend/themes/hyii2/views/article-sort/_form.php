<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ArticleSort */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-sort-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sort_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order')->textInput() ?>

    <?= $form->field($model, 'inner_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'language')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'datetime')->textInput() ?>

    <?= $form->field($model, 'orders')->textInput() ?>

    <div class="form-group">
    
        <?= Html::submitButton('保存' , ['class' =>'btn btn-success green']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
