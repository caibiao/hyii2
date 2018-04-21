<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel Service\DataOp\Models\Search */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Depts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dept-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Dept', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'no',
            'name',
            'name_en',
            'name_ru',
            // 'name_big',
            // 'parent_no',
            // 'country',
            // 'area:ntext',
            // 'inner_no',
            // 'role',
            // 'datetime:datetime',
            // 'service_proportion',
            // 'consume_proportion',
            // 'coins_web',
            // 'coins',
            // 'sms_nums',
            // 'bank',
            // 'account',
            // 'account_name',
            // 'mobile',
            // 'tel',
            // 'address',
            // 'postalcode',
            // 'email:email',
            // 'stock',
            // 'order_credit',
            // 'goods_credit',
            // 'award_flag',
            // 'discount',
            // 'currency_id',
            // 'flag',
            // 'has_coins',
            // 'user_no',
            // 'intro_no',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
