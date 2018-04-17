$(function(){

    $('.detail-add-button').click(function() {
        $('#detail-add-modal').modal('show').find('#detail-add-modal-view').html('');
        $('#detail-add-modal').modal('show').find('#detail-add-modal-view').load($(this).attr('href'));
        return false;
    });

    $('.update-button').click(function() {
        $('#detail-add-modal').modal('show').find('#detail-add-modal-view').html('');
        $('#detail-add-modal').modal('show').find('#detail-add-modal-view').load($(this).attr('href'));
        return false;
    });

    $('.disabled-button').click(function() {
      $btn = $(this);
      if(confirm('确定停用吗?')) {
        $.ajax({
          url: $btn.attr('href'),
          dataType: 'json',
        })
        .success(function(json) {
          $btn.hide();
          $btn.parent().find('.enabled-button').show();
        });
      }
      return false;
    });

    $('.enabled-button').click(function() {
      $btn = $(this);
      if(confirm('确定启用吗?')) {
        $.ajax({
          url: $btn.attr('href'),
          dataType: 'json',
        })
        .success(function(json) {
          $btn.hide();
          $btn.parent().find('.disabled-button').show();
        });
      }
      return false;
    });

});
