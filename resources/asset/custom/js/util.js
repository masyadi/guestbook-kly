//init message
window.initMessage = () => {

    if (ANH.msg) {
        for (var i in ANH.msg) {
            toastr[ANH.msg[i].type](ANH.msg[i].text, ANH.msg[i].title)
        }
    }

}

//init Count
window.initCount = () => {

    if ($('.notif-count').length) {
        $.getJSON(ANH.url.web + '/remote?act=get-count', function(dt) {

            if (dt.notif && $('.notif-count').length) {

                //play audio 
                if (parseInt($('.notif-count').html()) != parseInt(dt.notif) && parseInt($('.notif-count').html()) != 0) {
                    window.notifAudio.playclip()
                }

                $('.notif-count').html(dt.notif)
            };

            if ($('[data-page=notif] .datatable').length) {
                $('[data-page=notif] .datatable').each(function() {
                    $(this).DataTable().ajax.reload();
                });
            }

        })
    }

    if ($('.total-blog').length || $('.total-page').length) {
        $.getJSON(ANH.url.web + '/remote?act=get-page-blog', function(dt) {

            if (dt.page && $('.total-page').length) $('.total-page').html(dt.page)

            if (dt.blog && $('.total-blog').length) $('.total-blog').html(dt.blog)

            if (dt.row_blog && $('.data-blog').length) {
                var tr = '';

                for (var i in dt.row_blog) {
                    var r = dt.row_blog[i]

                    tr += '<tr><td>\
                            ' + r.title + '\
                          </td><td class="fit">\
                            ' + r.relcategory.title + '\
                          </td><td class="fit">\
                            ' + r.relauthor.name + '\
                          </td><td class="fit">\
                            ' + r.publish_date + '\
                          </td><td class="fit">\
                            <a href="' + ANH.url.base + '/blog/' + r.id + '/edit" class="btn btn-sm btn-primary"><i class="fa fa-th"></i> lihat</a>\
                          </td></tr>';
                }

                $('.data-blog tbody').html(tr)
            }

            if (dt.row_page && $('.data-page').length) {
                var tr = '';

                for (var i in dt.row_page) {
                    var r = dt.row_page[i]

                    tr += '<tr><td>\
                            ' + r.title + '\
                          </td><td class="fit">\
                            ' + r.relauthor.name + '\
                          </td><td class="fit">\
                            <a href="' + ANH.url.base + '/page/' + r.id + '/edit" class="btn btn-sm btn-primary"><i class="fa fa-th"></i> lihat</a>\
                          </td></tr>';
                }

                $('.data-page tbody').html(tr)
            }

        })
    }
}
window.initCount();


//anchor
$(document).on('click', '.btn-anchor', function() {
    var t = $(this).data('target');

    if ($(t).length) {
        $([document.documentElement, document.body]).animate({
            scrollTop: $(t).offset().top
        }, 2000);

        $(t).click();
    }

    return false;
});

//init menu
if ($('.sub-level').length) {
    $('.sub-level li.active').each(function() {
        $(this).closest('.sub-level').parent().addClass('active');
    });
}

if ($('.nestable').length) {
    $('.nestable').nestable({
        group: 1
    }).on('change', function(e) {
        var data = 'sort=' + JSON.stringify($(this).nestable('serialize'));
        $.ajax({
            type: 'POST',
            data: data + '&_token=' + ANH.token,
            success: function(dt) {
                if (dt == '1') toastr['success']('Menu has been sorted!', 'SUCCESS');
            },
            error: function(dt) {
                toastr['error']('An error occurred, please try again', 'ERROR');
            }
        })
    });

    $(document).on('click', '#nestable-toggle', function() {

        var state = $(this).attr('state') || 'open';

        if (state == 'open') {
            $('.nestable').nestable('collapseAll');
            $(this).attr('state', 'close');
            $(this).text('Buka Semua');
        } else {
            $('.nestable').nestable('expandAll');
            $(this).attr('state', 'open');
            $(this).text('Tutup Semua');
        }

        return false;
    });

}


if ($('#modal-autoload').length) {
    $('#modal-autoload').modal('show');
}

initMessage();

//HELP
$("#modalHelp").on('show.bs.modal', function() {
    $.ajax({
        type: 'GET',
        url: ANH.url.web + '/remote?act=get-help',
        data: 'page=' + ANH.url.page,
        success: function(dt) {
            if (dt.content) $("#modalHelp .modal-body").html(dt.content)
        },
        error: function(dt) {
            toastr['error']('An error occurred, please try again', 'ERROR');
        }
    })
});

//ACTIVE MENU
if ($('#side-menu .nav-second-level > li > .nav-second-level li.active').length) {
    $('#side-menu .nav-second-level > li > .nav-second-level li.active').parent().closest('li').closest('.nav-second-level').addClass('in').css('height', 'auto');
    $('#side-menu .nav-second-level > li > .nav-second-level li.active').parent().closest('li').closest('.nav-second-level').closest('li').addClass('active');
}

// disable class on click event
$(document).on('click', '.click-disable', function() {
    $(this).addClass('disabled');
});

// hide tooltion on click event
$(document).on('click', '[data-toggle="tooltip"]', function () {
    $('[data-toggle="tooltip"]').tooltip("hide");
 });


// FORMAT NUMBER
window.formatNumber = (number, thousand = ',') => {
    return number.toString().replace(/[.,\s]/g,'').replace(/(\d)(?=(\d{3})+(?!\d))/g, `$1${thousand}`);
}

// render again pluggin
window.renderPluggin = () => {

    if ($('.select2').length) {
        $('.select2').select2();
    }

    if ($('.datatable').length) {
        $('.datatable').each(function() {
            $(this).DataTable().ajax.reload();
        });
    }

    if ($('.form-group').length) {
        $('.form-group').each(function() {
            if ($(this).find('.form-control').attr('required')) {
                const labelElement = $(this).find('label');
                $(labelElement).find('.required').remove();
                $(labelElement).append(`<sup class="required">*</sup>`);
            }
        });
    }

    $('[data-toggle="tooltip"]').tooltip();
    autoFormatCurrency();
    initMessage();

    // reset toas message
    window.ANH.msg = [];
}

// ajax request
window.ajaxRequest = ({url, method, data, buttonElement, successCallback, errorCallback}) => {
    const form = $(buttonElement).closest('form');
    const btnText = $(buttonElement).text();
    const btnProcess = $(buttonElement).data('label') || 'Saving ...';

    $(buttonElement).attr('disabled', true);
    // $(buttonElement).html('<i class="fa fa-refresh fa-spin"></i> ' + btnProcess);

    // reset error
    $(form).find('.error-text').remove();
    $(form).find('.is-invalid').removeClass('is-invalid');

    // let data = new FormData();
    // const params = $(form).serializeArray();

    // $.each(params, function(i, val) {
    //     data.append(val.name, val.value);
    // });
    

    $.ajax({
        url: url,
        type: method,
        data: data,
        success: function(response) {

            if ( response.data != null) {
                if (response.data.message != null && typeof response.data.redirect == 'undefined') {
                    if (response.data.message.hasOwnProperty('text')) {
                        if (!response.data.message.hasOwnProperty('type')) {
                            response.data.message = {
                                ...response.data.message,
                                'type': 'success'
                            }
                        }

                        if (!response.data.message.hasOwnProperty('title')) {
                            response.data.message = {
                                ...response.data.message,
                                'title': response.data.message.type.toUpperCase()
                            };
                        }

                        ANH.msg.push(response.data.message);
                        window.initMessage();
                        window.ANH.msg = [];
                    }
                    
                }

                if (response.data.redirect != null) {
                    window.location.href = response.data.redirect;
                }
            }

            if (response.hasOwnProperty('message') && response.message != null) {
                const msg = {
                    'type': 'success',
                    'title': 'SUCCESS',
                    'text': response.message,
                };

                ANH.msg.push(msg);
                window.initMessage();
                window.ANH.msg = [];
            }

            if (successCallback != null) {
                successCallback(response);
                return;
            }
        },
        error: function(xhr, status, message) {
            const errors = xhr.responseJSON.errors;

            if (errorCallback != null) {
                errorCallback(xhr, status, message);
                return;
            }

            if (typeof errors === typeof undefined) {
                const msg = xhr.responseJSON.message;

                if (typeof msg !== typeof undefined) {
                    toastr['error'](msg, 'ERROR');
                    return;
                }
            }

            $.each(errors, function(key, val) {
                try {
                    let inputkey = null;
                    $.each(key.split('.'), function(index, val) {
                        if(index == 0) {
                            inputkey = val;
                        }
                        else {
                            inputkey += `[${val}]`;
                        }
                    });

                    // const input = $(form).find('[name^="'+ inputkey +'"]').closest('.form-group');
                    const input = $(form).find('[name^="'+ inputkey +'"]').parent();
                    
                    if (input.length) {
                        $(input).append('<span class="invalid-feedback error-text">'+ val +'</span>');
                        $(form).find('[name^="'+ inputkey +'"]').addClass('is-invalid');
                        return;
                    }

                    throw 401;
                
                } catch (error) {
                    toastr['error'](val, 'ERROR');
                }
            });
        },
        complete: function() {
            $(buttonElement).attr('disabled', false);
            // $(buttonElement).html('<i class="fa fa-refresh fa-save"></i> ' + btnText);
            autoFormatCurrency();
        }
    });
}