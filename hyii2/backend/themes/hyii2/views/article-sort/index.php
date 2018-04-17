<?php

use yii\helpers\Html;
use yii\helpers\Url;
use backend\widgets\common\LinkPages;

$this->title = '信息类别';
$this->params['breadcrumbs'][] = $this->title;

//$this->registerJsFile('/statics/js/table_base.js',[ 'depends'=> 'backend\assets\AppAsset']);
?>
<div class="auth-item-model-index">

    <div class ="search-container" >
        <?=Html::a("创建 <i class='icon-plus'></i>" , ['create' ], ['class' => 'btn btn-success green']) ?>
       <form action ="" class="form-search pull-right" >
            <div class ="input-append" >
                <select name ="type" class="search-option">
                    <option value ="name">名称</ option>
                </select >
                 <input name ="value" class="m-wrap" type="text" placeholder= "请输入搜索内容" >
                 <button class ="btn green" type= "submit">搜索</button >
            </div >
     </form >
    </div >
<div class="summary">
    <?=Yii::t('common' , '{start}-{end} a total of {total}',['start' => $data[ 'start'], 'end'=> $data['end'],'total'=> $data['count' ]])?> </div >
    <table class ="table table-striped table-bordered table_base">
        <thead >
            <tr >
                <!--<th>-->
                <!--<div class="checker"><span><input type="checkbox" value="1" name="select_all" class="select-on-check-all"></span></div> -->
                <!--</th> -->
                <th ># </th >
                <th >ID </th >
                <th >名称 </th >
                <th >类别标签</th >
                <th >排序</th>
                <th >操作</th>
            </tr >
        </thead >
        <tbody >
            <?php if (empty($data[ 'data'])): ?>
            <tr ><td colspan ="20"><?=Yii::t('common' ,'Not find data') ?> </td ></tr >
            <?php else: ?>
            <?php $i = $data['start'];?>
            <?php foreach ( $data['data'] as $list):?>
            <tr data-key =" <?=$list[ 'id'] ?>" >
                <!--<td> -->
                <!--<div class="checker"><span><input type="checkbox" value="158" name="select"></span></div> -->
                <!--</td> -->
                <td ><?= $i++?> </td >
                <td ><?= Html::encode($list[ 'id']) ?></ td>               
                <td ><?= Html::encode($list[ 'name']) ?></ td>
                <td ><?= Html::encode($list[ 'inner_no']) ?></td>
                <td ><?= Html::encode($list[ 'orders']) ?></ td>
                 <td>
                    <a href= "<?= Url::to([ 'update', 'id'=>$list[ 'id']]); ?>" >编辑 </a >
                     <a class ="del" href= "javascript:;">删除</a >
                </td>
            </tr >
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody >
    </table >
    <?=LinkPages:: widget(['pagination' => $pages]);?>
   <input type ="hidden" name="delUrl" value= "<?= Url::to([ 'delete']) ?>" >
</div>
