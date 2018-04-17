var modalInfo = $('#modal-info');
var modalAlert = $('#modal-alert');
var modalConfirm = $('#modal-confirm');
var modalSampleCartConfirm = $('#sample-cart-confirm');
var ANLEWO = {
    info: function (msg, type) {
        modalInfo.find('.modal-info').html(msg);
        if (type == '') {
            modalInfo.find('.modal-body i').hide();
        } else if (type == 'success') {
            modalInfo.find('.modal-body i').removeClass().addClass('fa fa-check-circle');
        } else if (type == 'error') {
            modalInfo.find('.modal-body i').removeClass().addClass('glyphicon glyphicon-remove-circle');
        }
        modalInfo.modal('show');
        return {
            on: function (callback) {
                if (callback && callback instanceof Function) {
                    modalInfo.find('.ok').click(function () {
                        callback(true)
                    });
                }
            }
        };
    },
    alert: function (msg, type, isStatic) {
        modalAlert.find('.modal-info').html(msg);
        if (type == '') {
            modalAlert.find('.modal-body i').hide();
        } else if (type == 'success') {
            modalAlert.find('.modal-body i').removeClass().addClass('fa fa-check-circle');
        } else if (type == 'error') {
            modalAlert.find('.modal-body i').removeClass().addClass('glyphicon glyphicon-remove-circle');
        }
        if (isStatic) {
            modalAlert.modal({backdrop: 'static', keyboard: false, show:true});
        } else {
            modalAlert.modal('show');
        }
        return {
            on: function (callback) {
                if (callback && callback instanceof Function) {
                    modalAlert.find('.ok').click(function () {
                        callback(true)
                    });
                }
            }
        };
    },
    confirm: function (msg) {
        modalConfirm.find('.modal-info').html(msg);
        modalConfirm.modal({backdrop: 'static', keyboard: true});
        modalConfirm.modal('show');
        return {
            on: function (callback) {
                if (callback && callback instanceof Function) {
                    modalConfirm.find('.ok').click(function () {
                        modalConfirm.find('.ok').unbind();
                        callback(true);
                    });
                    modalConfirm.find('.cancel').click(function () {
                        modalConfirm.find('.ok').unbind();
                        modalConfirm.find('.cancel').unbind();
                        callback(false);
                    });
                }
            }
        };
    },
    sampleCartConfirm: function (msg) {
        if (msg == 1) {
            modalSampleCartConfirm.find('.warning-info').show();
            modalSampleCartConfirm.find('.confirm-info').removeClass('modal-info');
        } else {
            modalSampleCartConfirm.find('.warning-info').hide();
            modalSampleCartConfirm.find('.confirm-info').addClass('modal-info');
        }
        modalSampleCartConfirm.modal('show');
        return {
            on: function (callback) {
                if (callback && callback instanceof Function) {
                    modalSampleCartConfirm.find('.ok').click(function () {
                        modalSampleCartConfirm.find('.ok').unbind();
                        callback(true);
                    });
                    modalSampleCartConfirm.find('.cancel').click(function () {
                        modalSampleCartConfirm.find('.ok').unbind();
                        modalSampleCartConfirm.find('.cancel').unbind();
                        callback(false);
                    });
                }
            }
        };
    }
};