
// replace yii2 default confirm dialog 
yii.confirm = function (message, okCallback, cancelCallback) {
    swal({
        title: "Παρακαλώ επιβεβαιώστε",
        text: message,
        type: 'warning',
        showCancelButton: true,
        closeOnConfirm: true,
        allowOutsideClick: true,
        confirmButtonText: 'OK',
        confirmButtonColor: '#5cb85c',
        cancelButtonText: 'Άκυρο',
        showLoaderOnConfirm: true
    }, okCallback);
};
