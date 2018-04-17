<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DatadictDetail */

?>
<div class="update">

    <?= $this->render('_form', [
        'model' => $model,
        'pid' => $model->pid,
    ]) ?>

</div>
