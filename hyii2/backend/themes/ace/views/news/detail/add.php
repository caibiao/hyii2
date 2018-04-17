<?php

use yii\helpers\Html;

?>
<div class="create">
    <?= $this->render('_form', [
        'model' => $model,
        'pid' => $model->pid,
    ]) ?>
</div>
