<?php
use yii\helpers\Url;
use abiao\widgets\Button;
use abiao\widgets\Atag;
use yii\helpers\Html;
use Anlewo\Common\Models\Procure;
use Anlewo\Common\Models\BaseOrders;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use Anlewo\SDK\Bridge\GoodsClassTable;
use Anlewo\SDK\Gateway\ApprovalTable;
use Anlewo\Common\Anlewo\Constant;
use Anlewo\Common\Models\factory;

$actionId = Yii::$app->request->get('actionId', 'detail');
$isNew = $vehiclesModel->isNewRecord ? 1 : 0;

if ($actionId == 'detail') {
    $title = '车辆管理查看';
} elseif ($actionId == 'edit') {
    $title = '车辆管理编辑';
}

$this->title = $title;

$index = Url::toRoute(['index']);
$userId = Yii::$app->user->identity->id;
$saveUrl = Url::toRoute(['save-vehicles']);
$validateUrl = Url::toRoute(['validate-form']);
$info = Url::toRoute(['info']);

$js = <<<JS
    var isTrue = true;
    //保存信息
    $("#save-vehicles").click(function(){

      if($("#vehicles-mobile").val() == ''){
        $(".help-block").css("color","red");
        $("#vehicles-mobile").next().text('联系电话不能为空。');
        return false;
      }

      if($("#vehicles-username").val() == ''){
        $(".help-block").css("color","red");
        $("#vehicles-username").next().text('司机姓名不能为空。');
         return false;
      }

      var tun = $('input[name="Vehicles[tonnage][]"]');
      var bei = $('input[name="Vehicles[remarks][]"]');
      var plate = $('input[name="Vehicles[licensePlate][]"]');

      var itemArr = new Array();

        plate.each(function(n,m){
    
            //判断多个车牌号是否存在相同
            if($.inArray($(this).val(), itemArr) == -1){
                itemArr.push($(this).val());
            }else{
                isTrue = false;
                $(this).next('.help-block').html($(this).val() + '已存在');
                $(this).next('.help-block').attr('isTrue','1');
                return true;
            }
    
            if(tun.eq(n).val() != '' || bei.eq(n).val() != '' || $(this).val() != ''){
                checkLicensePlate(this);
            }
    
            if($(this).next('.help-block').attr('isTrue') == 1){
                isTrue = false;
            }
        });
    
        if($(".help-block").text().length > 0){
           return false; 
        }
        
        if(isTrue){
            ANLEWO.confirm('确定要执行操作吗？').on(function (e) {
              if(!e) return false;
              
              $.ajax({
                url: '{$saveUrl}',
                type: 'post',
                cache: false,
                data: $("#vehiclesModel").serialize(),
              }).done(function(res) {
                if(res.success) {
                    ANLEWO.alert('操作成功', 'success').on(function(e){
                        location.href = '{$info}' + '?id=' + res.msg + '&actionId=detail';
                    });
                }else{
                  ANLEWO.alert(res.msg, 'error');
                }
              }).fail(function(res) {
                  ANLEWO.alert('>_<, 服务器受到一个爆击导致失败，请稍后再试~ ', 'error')
              });
              
            });
        }

      return false;
    });

    //删除车辆信息
    $(".del-vehicles").click(function(){
      var url = $(this).attr('data-url');
      ANLEWO.confirm('确定要删除吗？').on(function (e) {
          if(!e) return false;
          $.ajax({
            url: url,
            type: 'get',
            cache: false,
          }).done(function(res) {
            if(res.success) {
              ANLEWO.alert('删除车辆信息成功', 'success').on(function(e){
                  location.href = '{$index}';
              });
            }else{
              ANLEWO.alert(res.msg, 'error');
            }
          }).fail(function(res) {
              ANLEWO.alert('>_<, 服务器受到一个爆击导致失败，请稍后再试~ ', 'error')
          });
      });
      return false;
    });

    $('#license-plate-number').click(function(){
        var html = $('#license').find('tr:first').clone();
        html.find('input').val('');
        html.find('textarea').val('');
        html.find('.help-block').html('');
        $('#license').append(html);
    });

    // 验证车牌号码
    function checkLicensePlate(id){
        $.ajax({
            url: '{$validateUrl}',
            type: 'post',
            cache: false,
            data: {plateNum:$(id).val(),userName:$('#vehicles-username').val(),mobile:$('#vehicles-mobile').val(),id:$(id).attr('data-id')},
          }).done(function(res) {
            if(!res.success){
                isTrue = false;
                $(id).next('.help-block').html(res.msg);
                $(id).next('.help-block').attr('isTrue','1');
            }else{
                isTrue = true;
                $(id).next('.help-block').html(res.msg);
                $(id).next('.help-block').attr('isTrue','0');
            }

          }).fail(function(res) {
              ANLEWO.alert('>_<, 服务器受到一个爆击导致失败，请稍后再试~ ', 'error')
          });
    }
JS;
$this->registerJs($js, Yii\web\View::POS_END);
?>
<style type="text/css" media="screen">
    .help-block: {
        color:red;
    }
</style>
<section class="content-header">
    <h1>
        <?= $title ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= Url::toRoute(['index']) ?>">车辆管理</a></li>
        <li class="active"><?= $title ?></li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="box">
        <div class="box-header">
            <?php if ($actionId == 'detail' && isset($vehiclesModel->id)) { ?>
                <?= Atag::widget([
                    'text' => '编辑',
                    'visabled' => false,
                    'route' => ['/Vehicles/vehicles/edit', 'id' => $vehiclesModel->id, 'actionId' => 'edit'],
                    'options' => ['class' => 'btn btn-purple btn-w82 mr5'],
                ]) ?>
            <?php } elseif ($actionId == 'edit') { ?>
                <?= Button::widget([
                    'id' => 'save-vehicles',
                    'label' => '保存',
                    'route' => $isNew == 1 ? ['/Vehicles/vehicles/add-vehicles'] : ['/Vehicles/vehicles/save-vehicles'],
                    'options' => [
                        'class' => 'btn btn-purple btn-w82 mr5',
                        'type' => 'button',
                    ]
                ]) ?>
            <?php } ?>
            <?php if (isset($vehiclesModel->id)) { ?>
                <?= Atag::widget([
                    'text' => '删除',
                    'visabled' => false,
                    'route' => ['/Vehicles/vehicles/del-vehicles', 'userName' => $vehiclesModel->userName],
                    'options' => [
                        'class' => 'del-vehicles btn btn-default btn-w82',
                        'data-url' => Url::toRoute(['del-vehicles', 'userName' => $vehiclesModel->userName])
                    ],
                ]) ?>
            <?php } ?>
        </div>

        <div class="box box-default">
            <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-Nomargin'],
                'id' => 'vehiclesModel',
                'action' => Url::toRoute(['save-vehicles']),
                'method' => 'post',
            ]);
            ?>
            <div class="box-header with-border">
                <h3 class="box-title">司机信息</h3>
            </div>
            <div class="box-body no-padding">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <td colspan="3"><span
                                    class="start-icon <?php if ($actionId == 'edit') echo 'start-position'; ?>">*</span>
                            <?php if ($actionId == 'detail' || $isNew == 0) { ?>
                                <b>司机姓名：</b><?= $vehiclesModel->userName; ?>
                                <?= $form->field($vehiclesModel, 'userName', [
                                    'inputOptions' => [
                                        'class' => 'form-control select-warp-option',
                                    ],
                                ])->hiddenInput([])->label(false);
                                ?>
                            <?php } else {
                                echo $form->field($vehiclesModel, 'userName', [
                                    'inputOptions' => [
                                        'class' => 'form-control select-warp-option',
                                    ],
                                ]);
                            } ?>
                        </td>
                        <td colspan="3">
                            <?php if ($actionId == 'detail') { ?>
                                <b>身份证号：</b><?= $vehiclesModel->idCard; ?>
                            <?php } else {
                                echo $form->field($vehiclesModel, 'idCard', [
                                    'inputOptions' => [
                                        'class' => 'form-control select-warp-option',
                                    ],
                                ]);
                            } ?>
                        </td>
                        <td colspan="3"><span
                                    class="start-icon <?php if ($actionId == 'edit') echo 'start-position'; ?>">*</span>
                            <?php if ($actionId == 'detail') { ?>
                                <b>联系电话：</b><?= $vehiclesModel->mobile; ?>
                            <?php } else {
                                echo $form->field($vehiclesModel, 'mobile', [
                                    'inputOptions' => [
                                        'class' => 'form-control select-warp-option',
                                    ],
                                ]);
                            } ?>
                        </td>
                    </tr>
                    </tbody>
                </table>


            </div>

            <div class="box-header with-border" style="margin-top: 50px;">
                <h3 class="box-title">车辆信息</h3>
                <?php if ($actionId != 'detail') { ?>
                    <button type="button" class="btn btn-orange btn-w82 ml5" id="license-plate-number">新增</button>
                <?php } ?>
            </div>
            <div class="box-body no-padding">
                <table class="table table-bordered" id="license">

                    <?php
                    if (!empty($listItem)) {
                        foreach ($listItem as $key => $val) {
                            ?>
                            <tr>
                                <td colspan="3">
                                    <?php echo Html::activeHiddenInput($vehiclesModel, 'id[]', ['value' => $val->id]); ?>
                                    <span class="start-icon <?php if ($actionId == 'edit') echo 'start-position'; ?>">*</span>
                                    <?php if ($actionId == 'detail') { ?>
                                        <b>车牌号：</b><?= $val->plateNum; ?>
                                    <?php } else {
                                        echo $form->field($vehiclesModel, 'licensePlate[]', [
                                            'inputOptions' => [
                                                'class' => 'form-control select-warp-option',
                                                'onblur' => 'checkLicensePlate(this)',
                                                'value' => $val->plateNum,
                                                'data-id' => $val->id,
                                            ],
                                        ])->label('车牌号');
                                    } ?>
                                </td>
                                <td colspan="3">
                                    <?php if ($actionId == 'detail') { ?>
                                        <b>吨位：</b><?= $val->ton; ?>
                                    <?php } else {
                                        echo $form->field($vehiclesModel, 'tonnage[]', [
                                            'inputOptions' => [
                                                'class' => 'form-control select-warp-option',
                                                'value' => $val->ton,
                                            ],
                                        ])->label('吨位');
                                    } ?>
                                </td>

                                <td colspan="3">
                                    <?php if ($actionId == 'detail') { ?>
                                        <b>备注：</b><?= $val->remark; ?>
                                    <?php } else {
                                        echo $form->field($vehiclesModel, 'remarks[]', [
                                            'inputOptions' => [
                                                'class' => 'form-control select-warp-option',
                                                'value' => $val->remark,
                                            ],
                                        ])->textarea()->label('备注');
                                    } ?>
                                </td>
                            </tr>

                        <?php }
                    } else {
                        ?>
                        <tr>
                            <td colspan="3">
                                <?php echo Html::activeHiddenInput($vehiclesModel, 'id[]', ['value' => '']); ?>
                                <span class="start-icon <?php if ($actionId == 'edit') echo 'start-position'; ?>">*</span>
                                <?php
                                echo $form->field($vehiclesModel, 'licensePlate[]', [
                                    'inputOptions' => [
                                        'class' => 'form-control select-warp-option',
                                        'onblur' => 'checkLicensePlate(this)',
                                    ],
                                ])->label('车牌号');
                                ?>
                            </td>
                            <td colspan="3">
                                <?php
                                echo $form->field($vehiclesModel, 'tonnage[]', [
                                    'inputOptions' => [
                                        'class' => 'form-control select-warp-option',
                                    ],
                                ])->label('吨位');
                                ?>
                            </td>

                            <td colspan="3">
                                <?php
                                echo $form->field($vehiclesModel, 'remarks[]', [
                                    'inputOptions' => [
                                        'class' => 'form-control select-warp-option',
                                    ],
                                ])->textarea()->label('备注');
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</section><!-- /.content-wrapper -->
<style type="text/css">
    .content-wrapper {
        margin-bottom: -20px;
    }
    .form-control{
        width:80%;
        margin-top: -25px;
        margin-left: 60px;
    }
    .form-group{
        padding-left:30px;

    }
    .start-icon{
        position:absolute;
        margin-top: 10px;
    }


</style>