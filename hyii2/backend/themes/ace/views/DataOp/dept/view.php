<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model Service\DataOp\Models\Dept */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Depts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dept-view">

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
            'no',
            'name',
            'name_en',
            'name_ru',
            'name_big',
            'parent_no',
            'country',
            'area:ntext',
            'inner_no',
            'role',
            'datetime:datetime',
            'service_proportion',
            'consume_proportion',
            'coins_web',
            'coins',
            'sms_nums',
            'bank',
            'account',
            'account_name',
            'mobile',
            'tel',
            'address',
            'postalcode',
            'email:email',
            'stock',
            'order_credit',
            'goods_credit',
            'award_flag',
            'discount',
            'currency_id',
            'flag',
            'has_coins',
            'user_no',
            'intro_no',
        ],
    ]) ?>

</div>
