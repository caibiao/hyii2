<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ArticleSort */

$this->title = 'Create Article Sort';
$this->params['breadcrumbs'][] = ['label' => 'Article Sorts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-sort-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
