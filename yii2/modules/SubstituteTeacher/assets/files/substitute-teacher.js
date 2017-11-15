
window.substitute_teacher_module = (function ($) {
    var module = {
        red: '#337ab7',
        green: '#5cb85c',

        alert_fileuploaddone: function () {
            swal({
                title: "Επιτυχία",
                type: "success",
                closeOnConfirm: true,
                allowOutsideClick: true,
                confirmButtonColor: module.green,
                showLoaderOnConfirm: true
            });
        },

        alert_fileuploadfail: function () {
            swal({
                title: "Αποτυχία",
                type: "error",
                closeOnConfirm: true,
                allowOutsideClick: true,
                confirmButtonColor: module.red,
                showLoaderOnConfirm: true
            });
        }

    };

    return module;
})(window.jQuery);
