<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model Anlewo\Order\models\ManageColumn */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Manage Columns', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="manage-column-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'type',
            'len',
            'name',
            'position',
            'featurePos',
            'remark',
            'orders',
            'operator',
            'datetime',
        ],
    ]) ?>

</div>
