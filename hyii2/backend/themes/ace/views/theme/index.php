<?php
use yii\helpers\Html;
use yii\helpers\Url;
/**
 * Created by PhpStorm.
 * User: hzhuangxianan
 * Date: 2016/11/23
 * Time: 18:22
 */
$this->title = '模板';
$this->params['breadcrumbs'][] = ['url'=>['theme/index'],'label'=>'模板管理'];
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?></h1>
<div class="theme-list">
    <ul>
        <?php foreach($themes as $theme): ?>
        <li data-id="<?=$theme['filename']?>">
            <div>
                <a href="#"><img src="<?=$theme['image_url']?>" alt="<?=$theme['filename']?>主题模板" class="img-thumbnail"></a>
            </div>
            <div class="theme-desc">
                <span class="theme-name"><b>模板名称：</b><?=$theme['filename']."主题"?></span>
                <span class="pull-right">
                    <?php if($theme['is_used']):?>
                        <a class="btn btn-danger">已启用</a>
                    <?php else:?>
                        <a class="btn btn-success j-set-theme" data-id = "<?=$theme['filename']?>">启用</a>
                    <?php endif;?>
                </span>
            </div>
        </li>
        <?php endforeach;?>
    </ul>
</div>
<?php
$requestUrl = Url::to(['set-theme']);
$js = <<<js
$(".j-set-theme").on('click',function(){
    var theme = $(this).attr('data-id');
    $.ajax({
        type:"POST",
        url:"{$requestUrl}",
        data:{tid:theme},
        dataType:"json",
        success:function(data){
            if(data.state){
                window.location.reload();
            }else{
                alert(data.msg);
            }
        }
    })
})
js;
$this->registerJs($js);
