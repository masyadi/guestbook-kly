// Navigation
window.navigate = function(url, target = null) {

    if (typeof url == 'undefined' || url == '' || url == '#') return false;
     
    if (target == '__blank') {

        window.open(url);
        return false;
    }

    window.location.href = url;
}

if ($('.locale-option').length) {
    $('.locale-option').each(function() {
        $(this).on('change', function(e) {
            $.get(ANH.url.web + '/remote?act=change-locale',{'locale': $(this).val()}, function(response) {
                window.location.reload();
            });
        });
    });
}