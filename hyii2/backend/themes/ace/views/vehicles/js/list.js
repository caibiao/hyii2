$(function () {

    // 批量删除
    $('#vehiclesDel').on('click', function () {
        var ids = $('#GridViewArea').yiiGridView('getSelectedRows');
        button('loading');

        if (ids.length == 0) {

            ANLEWO.alert('请勾选删除数据', 'error').on(function () {
                button('reset');
            });
            return false;
        }

        ANLEWO.confirm('确认删除？').on(function (ok) {

            if (!ok) {
                button('reset');
                return false;
            }

            $.ajax({
                type: 'POST',
                url: _opts.delUrl,
                data: {ids: ids},
            }).done(function (data) {
                button('reset');
                if (data.success) {
                    ANLEWO.alert(data.msg, 'success').on(function () {
                        location.href = data.redirect;
                        //location.reload();
                    });
                } else {
                    ANLEWO.alert(data.msg, 'error');
                }
            }).fail(function () {
                button('reset');
                ANLEWO.alert(">_<, 出错了，请稍后再试或联系技术部~ ", 'error');
            });
        })
        return false;
    });

    // 新增
    $('#addVehicles').on('click',function(){
        console.log(_opts.addVehiclesUrl);
        location.href = _opts.addVehiclesUrl;
    });
    // 导出
    $('#exportVehicles').on('click',function(){
        console.log(_opts.exportVehiclesUrl);
        location.href = _opts.exportVehiclesUrl;
    });

    // 导入
    $("#importBtn").click(function(){


        var _csrf = $("#_csrf").val();

        var v = $("#myFile").val();
        if(v === ""){
            ANLEWO.alert("请选择文件","error");
            return false;
        }
        var cururl = $("#importForm").attr("action");
        cururl += "?_csrf="+_csrf;
        $("#importForm").attr("action",cururl)
        $("#importForm").submit();
    });

});

var button = function (status) {
    $('vehiclesDel').button(status);
}

