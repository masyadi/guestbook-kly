if ($('.validate-form').length) {

    $('.validate-form').each(function() {
        var id = $(this).attr('id');
        if (!id) {
            id = Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, 5);

            $(this).attr('id', id);
        }

        $('#' + id).validate();

        $(document).on('submit', '#' + id, function() {

            var btnText = $(this).find('button[type=submit]').text();
            var btnProcess = $(this).find('button[type=submit]').data('label') || 'Saving ...';

            $(this).find('button[type=submit]').attr('disabled', true);
            $(this).find('button[type=submit]').html('<i class="fa fa-refresh fa-spin"></i> ' + btnProcess);

            var v = $(this).validate();

            if (v.valid()) {
                // currency format to number
                if ($('.currency-format').length) {
                    $('.currency-format').each(function() {
                        $(this).val($(this).data('value'));
                    });
                }

                return true;
            } else {
                $(this).find('button[type=submit]').attr('disabled', false);
                $(this).find('button[type=submit]').html('<i class="fa fa-refresh fa-save"></i> ' + btnText);
                return false;
            }

        });

    });
}

if ($('input[name=show_all]').length) {
    $('input[name=show_all]').on('ifChanged', function(event) {
        if (event.target.checked) {
            if (event.target.value == 1) {
                $('#rowArea').hide()
                $('#rowArea').find('select').attr('disabled', true);
            } else {
                $('#rowArea').show()
                $('#rowArea').find('select').attr('disabled', false);
            }
        }
    });
}

if ($('.select2').length) {
    $('.select2').select2();
}
if ($('.tNumb').length) {
    $(document).on('keyup', '.tNumb', function() {
        var string = numeral($(this).val()).format('0,0');
        $(this).val(string);
    });
    $('.tNumb').each(function() {
        var string = numeral($(this).val()).format('0,0');
        $(this).val(string);
    });
    $(document).on('submit', 'form', function() {
        $('.tNumb').each(function() {
            var value = numeral($(this).val()).value();
            $(this).val(value);
        });
    });
}

if ($('.tDate').length) {
    // start date
    let startDate = $('.tDate').data('min-date');

    if(typeof startDate !== typeof undefined) {
        startDate = new Date(startDate);
        startDate.setDate(startDate.getDate());
    }

    $('.tDate').datetimepicker({
        timepicker: false,
        format: 'Y/m/d',
        scrollMonth: false,
        scrollInput: false,
        minDate: typeof startDate === typeof undefined ? -1 : startDate,
        startDate: (typeof startDate === typeof undefined || $('.tDate').val() != '') ? null : new Date(startDate),
    });
}

if ($('.tDateTime').length) {
    $('.tDateTime').datetimepicker({
        timepicker: true,
        mask: false,
        scrollMonth: false,
        scrollInput: false
    });
}

window.initToken = (e, urlParams) => {
    //DEFINE
    var value, max, callbackOnAdd, callbackOnDelete, callbackFormat;
    var url = e.data('url');
    var id = e.attr('id');

    //TAG ID
    if (!id) {
        id = Math.random().toString(36).substring(7);
        e.attr('id', id);
    }

    //VALUE VARIABLE
    if (e.val()) value = JSON.parse(e.val());
    if (e.data('max')) max = e.data('max');
    if (e.data('onadd')) callbackOnAdd = e.data('onadd');
    if (e.data('ondelete')) callbackOnDelete = e.data('ondelete');
    if (e.data('format')) callbackFormat = e.data('format');

    //CALL PLUGIN
    $("#" + id).tokenInput(url + urlParams, {
        minChars: 1,
        preventDuplicates: true,
        prePopulate: value,
        tokenLimit: max,
        onAdd: function(item) { if (callbackOnAdd) window[callbackOnAdd](item, id); },
        onDelete: function(item) { if (callbackOnDelete) window[callbackOnDelete](item, id); },
        tokenFormatter: function(item) { if (callbackFormat) { return window[callbackFormat](item) } else { return "<li class=\"token-input-token\"><p>" + item.name + "</p></li>"; }; },
        resultsFormatter: function(item) { if (callbackFormat) { return window[callbackFormat](item) } else { return "<li class=\"token-input-token\"><p>" + item.name + "</p></li>"; }; }
    });
}

if ($('.input-token').length) {
    $('.input-token').each(function() {

        initToken($(this), '')

    });
}

if ($('[data-role="numeric"]').length) {
    $(document).on('keypress keyup blur', '[data-role="numeric"]', function(e) {
        $(this).val($(this).val().replace(/[^0-9\+]/g, ''));

        if ((e.which != 43 || $(this).val().indexOf('+') != -1) && (event.which < 48 || event.which > 57)) {
            e.preventDefault();
        }
    });

}

if ($('.editor').length) {
    var ImageBank = function(context) {
        var ui = $.summernote.ui;

        // create button
        var button = ui.button({
            contents: '<i class="fa fa-picture-o"/>',
            tooltip: 'Insert Image',
            click: function() {
                EDITOR_BROWS = context
                $('#modalImageBankPopup').modal('show');
            }
        });

        return button.render(); // return button as jquery object
    }

    $('.editor').each(function() {
        $(this).summernote({
            toolbar: $(this).attr('less') ? [
                ['style', ['bold', 'italic', 'underline']],
                ['para', ['ul', 'ol']],
            ] : [
                ['paragraph', ['style']],
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', ['imagebank', 'link', 'video', 'table', 'hr']],
                ['misc', ['fullscreen', 'codeview', 'help']]
            ],
            height: $(this).attr('less') ? 200 : 400,
            buttons: {
                imagebank: ImageBank
            },
            placeholder: 'type starting with <br/> : alphabet for emoji',
            hint: [{
                match: /:([\-+\w]+)$/,
                search: function(keyword, callback) {

                    $.ajax({
                            url: 'https://api.github.com/emojis',
                            async: false
                        })
                        .then(function(data) {
                            window.emojis = Object.keys(data);
                            window.emojiUrls = data;
                        })
                        .done(callback);

                    callback($.grep(emojis, function(item) {
                        return item.indexOf(keyword) === 0;
                    }));
                },
                template: function(item) {
                    var content = emojiUrls[item];
                    return '<img src="' + content + '" width="20" /> :' + item + ':';
                },
                content: function(item) {
                    var url = emojiUrls[item];
                    if (url) {
                        return $('<img />').attr('src', url).css('width', 20)[0];
                    }
                    return '';
                },
            }]
        });
    });
}

if ($('.role-check').length) {
    $('.role-check').on('ifChanged', function(event) {

        if (event.target.checked) {
            $(this).closest('li').find('ol').find('.role-check').iCheck('check');
        } else {
            $(this).closest('li').find('ol').find('.role-check').iCheck('uncheck');
        }
    });
}

//page
if ($('.page-title').length && $('.page-slug').length) {
    $(document).on('keyup, blur, change, input', '.page-title', function() {

        $(this).closest('fieldset').find('.page-slug').val(slug($(this).val())+'.html');

    });
}

window.slug = (text) => {
    return text.toString().toLowerCase()
        .replace(/\s+/g, '-') // Replace spaces with -
        .replace(/[^\w\-]+/g, '') // Remove all non-word chars
        .replace(/\-\-+/g, '-') // Replace multiple - with single -
        .replace(/^-+/, '') // Trim - from start of text
        .replace(/-+$/, ''); // Trim - from end of text
};

//CALLBACK
window.selectedCountry = (item, id) => {

    $('input[name=city_id]').prev(".token-input-list").remove();

    initToken($('input[name=city_id]'), '&country_code=' + item.code);

}

//AUTO SAVE
if ($('.autosave').length) {
    $('.autosave').each(function() {
        var form = $(this).closest('form');
        var interval = $(this).data('interval') || 5;

        var intval = setInterval(function() {
            formAutoSave(form);
        }, interval * 1000 * 6); //1000 * 60
    });
}

function formAutoSave(form) {
    toastr.options.timeOut = 30000;
    toastr['info']('', '<i class="fa fa-refresh fa-spin"></i> Auto Saving...');
    toastr.options.timeOut = 1500;

    $.ajax({
        url: ANH.url.web + '/remote?act=autosave&page=' + ANH.url.page,
        type: form.attr('method'),
        data: form.serialize(),
        success: function(dt) {
            toastr.clear();
        },
        error: function(dt) {
            toastr.clear();
            toastr['error']('ERROR', 'Failed to save');
        },
    })
}


//LOOKUP
window.getLocation = (e, target, type) => {

    if ($(target).length) {
        if (e.value) {
            //toastr.options.timeOut = 30000;
            //toastr['info']('Mohon tunggu', '<i class="fa fa-refresh fa-spin"></i> LOADING');
            //toastr.options.timeOut = 1500;

            $(target).select2('destroy');

            $.getJSON(ANH.url.web + '/remote?act=lookup-location', { parent: e.value, type: type }, function(dt) {

                if (dt) {
                    opt = '';
                    for (var i in dt) {
                        opt += '<option value="' + dt[i].id + '" ' + ($(target).data('selected') == dt[i].id ? 'selected="selected"' : '') + ' >' + dt[i].name + '</option>';
                    }
                    $(target).html(opt);
                    $(target).select2();

                    $(target).change();
                }

                //toastr.clear()

            });
        }
    }

}

if ($('select[name=id_provinsi]').length) {
    $('select[name=id_provinsi]').change();
}

if ($('.btn-import').length) {
    var fileID = Math.random().toString(36).substr(2);

    $(document).on('click', '.btn-import', function() {

        $(this).closest('form').find('input[type=file]').attr('id', fileID);
        $(this).closest('form').find('input[type=file]').click();

        return false;
    });

    $(document).on('change', '#' + fileID, function(e) {

        if ($(this).val()) {
            var name = e.target.files[0].name;

            $('.btn-import').attr('disabled', true);
            $('.modal-footer button').attr('disabled', true);
            $('.btn-import').html('<i class="fa fa-refresh fa-spin"></i> Uploading <strong>' + name + '</strong> ...');

            $(this).closest('form').submit()
        }

    });

}

//REQUIRED
if ($('.form-group').length) {
    $('.form-group').each(function() {
        if ($(this).find('.form-control').attr('required')) {
            $(this).find('label').append('<sup class="required">*</sup>');
        }
    });
}

//format barang
window.tokenFormatBarang = (item) => {
    return '<li class="token-input-token">\
        <span style="color:blue; float:left;">' + item.name + '</span> <br/>\
        <small style="display: block;font-size:10px; font-weight: normal;">' + item.label + '</small>\
        <div class="clearfix"></div></li>';
}

// Handle ajax request
if ($('form.ajax-request').length) {
    $('form.ajax-request').each(function() {
        const form = $(this);
        const method = $(form).attr('method');
        const action = $(form).attr('action');
        const successRedirect = $(this).data('success-redirect');
        const button = $(form).find('button[type=submit]');
        
        $(button).on('click', function(e) {
            // currency format to number
            if ($('.currency-format').length) {
                $('.currency-format').each(function() {
                    $(this).val($(this).data('value'));
                });
            }

            const data = $(form).serialize();

            window.ajaxRequest({
                url: action,
                method: method,
                data: data,
                buttonElement: $(this),
                successCallback: function(response) {
                    // reset form field
                    $(button).closest('form').find('input, textarea').val('');

                    if (typeof successRedirect != 'undefined') {
                        window.location.href = successRedirect;
                    }
                }
            });
        });
    });
}

// Auto format currency input
window.autoFormatCurrency = () => {
    if ($('.currency-format').length) {
        $('.currency-format').each(function() {
            let input = $(this).val();
            input = input.replace(/[\D\s\._\-]+/g, "");
            input = input ? parseInt(input) : 0;
    
            // $(this).val(new Intl.NumberFormat('id-ID').format(input));
            $(this).val(formatNumber(input));
            $(this).data('value', input);
    
            $(this).not('input[type=number]').on('keyup', function(val) {
                let input = $(this).val();
                input = input.replace(/[\D\s\._\-]+/g, "");
                input = input ? parseInt(input) : 0;
                
                $(this).val(formatNumber(input));
                $(this).data('value', input);
            });
        });
    }
}

// init autoFormatCurrency
window.autoFormatCurrency();


if($('.province-option').length && $('.city-option').length) {

    $(document).ready(function() {

        $('.province-option').trigger('change');
    });
    
    $('.province-option').on('change', function() {
        $.get(ANH.url.base +'/city', {'kode': $(this).val()}, function(result) {

            

            if(result) {
                const selected = $('.city-option').data('selected');
                $('.city-option option').remove();
                $.each(result, function(key, item) {
                    $('.city-option').append(`
                        <option value="${key}" ${selected == key ? 'selected' : ''}>${item}</option>
                    `);
                });
            }
        });
    });
}