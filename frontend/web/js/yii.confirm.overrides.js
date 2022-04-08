
// yii.confirm = function (message, okCallback, cancelCallback) {
yii.confirm = function (message, okCallback) {
    swal({
        title: message,
        type: 'warning',
        showCancelButton: true,
        closeOnConfirm: true,
        allowOutsideClick: true
    }, okCallback);
};
