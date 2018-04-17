$(function(){

    $('#add-button').click(function() {
        $('#datadict-add-modal').modal('show').find('#datadict-add-modal-view').html('');
        $('#datadict-add-modal').modal('show').find('#datadict-add-modal-view').load($(this).attr('href'));
        return false;
    });

    $('.update-button').click(function() {
        $('#datadict-add-modal').modal('show').find('#datadict-add-modal-view').html('');
        $('#datadict-add-modal').modal('show').find('#datadict-add-modal-view').load($(this).attr('href'));
        return false;
    });

    // $('#datadict-add-modal').on('hide.bs.modal', function() {
    //     // $.pjax.reload({container:'#datadict-add-gridview'});
    // });
});
