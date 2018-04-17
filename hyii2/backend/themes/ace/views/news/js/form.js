$('form').off('beforeSubmit').on('beforeSubmit', function(e) {
    var $yiiform = $(this);

    $.ajax({
        type: $yiiform.attr('method'),
        url: $yiiform.attr('action'),
        data: new FormData(($yiiform)[0]),
        processData: false,
        contentType: false,
        success: function (data) {
            if (data.success) {
                alert("更新成功");
                setTimeout(function(){
                    location.href = data.redirect;
                },1000);
            } else if (data.validation) {
                $yiiform.yiiActiveForm('updateMessages', data.validation, true);
            } else {
                alert(data.msg);
            }
        },
        error: function () {
            alert("Something wrong");
        }
    });
    return false;
})
