import Echo from "laravel-echo"

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    wsHost: window.location.hostname,
    wsPort: process.env.MIX_LARAVEL_WEBSOCKETS_PORT,
    disableStats: true,
});

/**
 * ON SAVED 
 **/
window.Echo.channel('AppChannel')
    .listen('OnSaved', (e) => {

        window.initCount();

        if ($('[data-page=' + e.page + '] .datatable').length) {

            toastr['info']('Data telah diperbarui', 'INFO')

            $('[data-page=' + e.page + '] .datatable').each(function() {
                $(this).DataTable().ajax.reload();
            });
        }

    });

/** 
 * PRIVATE
 **/
if (ANH.auth) {

    window.Echo.private('users.' + ANH.auth.id)
        .notification((notification) => {

            window.initCount();

            if (notification.type == "App\\Notifications\\NotifMessage") {

                toastr.options = {
                    "closeButton": false,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": false,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": function() {
                        window.location.href = ANH.url.base + '/notif?r=' + notification.id
                    },
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }

                toastr['info'](notification.description, 'PESAN BARU');
            }
        });
}