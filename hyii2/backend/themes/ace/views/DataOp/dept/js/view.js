// 新增
$('.address_wrapper .add-item').click(function(){
    var city = $('#bigcustom-area').val();
    if (city == null || city == '') {
        ANLEWO.alert('请先完善注册地区', 'error');
        return false;
    }
    return true;
});

jQuery(".address_wrapper").on("afterInsert", function(e, item) {
    var provinceDefaultValue = $('#province').val();
    var cityDefaultValue = $('#bigcustom-area').val();
    var cityHtml = $('#bigcustom-area').html();
    var areaHtml = $('#bigcustom-areahide').html();
    $('.address_wrapper .item-address:last select:eq(0)').val(provinceDefaultValue).trigger('change');
    $('.address_wrapper .item-address:last select:eq(1)').prop("disabled", false).html(cityHtml).val(cityDefaultValue).trigger('change');
    $('.address_wrapper .item-address:last select:eq(2)').prop("disabled", false).html(areaHtml);
});
