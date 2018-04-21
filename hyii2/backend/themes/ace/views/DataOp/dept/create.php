<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model Service\DataOp\Models\Dept */

$this->title = 'Create Dept';
$this->params['breadcrumbs'][] = ['label' => 'Depts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dept-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
