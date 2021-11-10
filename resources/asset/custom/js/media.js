//INIT MEDIA
var LAST_FETCH = null;
var ONLYUPLOAD = false;
var WEBCAM = false;
var OLDVALUE = false;
var DZ = null;
var defaultAcceptedFile = 'image/*';

if ($('[data-toggle="tooltip"]').length) $('[data-toggle="tooltip"]').tooltip()

$(document).on('click', '.bank-action-starred', function() {

    imageBankAction($(this), 'star');

    return false;
});

$(document).on('click', '.bank-action-unstarred', function() {

    imageBankAction($(this), 'unstar');

    return false;
});

$(document).on('click', '.bank-action-trash', function() {

    imageBankAction($(this), 'trash');

    return false;
});

$(document).on('click', '.bank-action-restore', function() {

    imageBankAction($(this), 'restore');

    return false;
});

$(document).on('click', '.bank-action-edit', function() {

    imageBankAction($(this), $(this).attr('data-action'));

    return false;
});

function imageBankAction(e, action) {
    if (e.attr('disabled')) {
        return false;
    }

    e.attr('disabled', true);

    $.ajax({
        type: 'PATCH',
        url: e.attr('href'),
        data: '_token=' + ANH.token + '&action=' + action,
        success: function(dt) {
            if (dt.status) {
                toastr['success'](dt.message, 'SUCCESS');

                if (ANH.url.page == 'image-bank') {
                    setTimeout(function() {
                        window.location.reload();
                    }, 1500);
                } else {
                    fetchImage(LAST_FETCH);
                }
            } else {
                toastr['error'](dt.message, 'ERROR');
            }

            e.attr('disabled', false);
        },
        error: function(dt) {
            toastr['error']('An error occurred, please try again', 'ERROR');
            e.attr('disabled', false);
        }
    })
}

function fetchImage(url, autoSelect) {

    $.ajax({
        type: 'GET',
        url: url,
        success: function(dt) {
            $('#imagebank-list').html(dt);
            LAST_FETCH = url;

            if (autoSelect) {
                $('#imagebank-list .OpenModalPreview:first').click();
            }
        }
    });
}

if ($('#imagebank-uploader').length) {
    var FILES = [];
    var BUTTON_BROWS = null;

    DZ = $('#imagebank-uploader').dropzone({
        addRemoveLinks: true,
        parallelUploads: 1, // since we're using a global 'currentFile', we could have issues if parallelUploads > 1, so we'll make it = 1
        maxFilesize: 1024, // max individual file size 1024 MB
        chunking: true, // enable chunking
        // chunkSize: 1000000, // chunk size 1,000,000 bytes (~1MB)
        chunkSize: 2000000, // chunk size 1,000,000 bytes (~2MB)
        retryChunks: true, // retry chunks on failure
        retryChunksLimit: 3, // retry maximum of 3 times (default is 3)
        acceptedFiles: defaultAcceptedFile,
        // acceptedFiles: 'image/*,application/pdf,.doc,.docx,.xls,.xlsx,.csv,.tsv,.ppt,.pptx,.pages,.odt,.rtf',
        init: function () {
            this.on('addedfile', function (a) {
                this.options.acceptedFiles = $('.dz-hidden-input').attr('accept');
            });

            this.on("error", function (file) {

                if( file && !file.accepted)
                {
                    toastr['error']('Invalid attachment', 'ERROR');

                    $('.list-attachments').each(function() {
                        var tb = $(this).find('table');

                        tb.find('.loader').remove();

                        if( tb.find('tr:not(.empty)').length )
                        {
                            tb.find('tr.empty').hide()
                        }
                        else
                        {
                            tb.find('tr.empty').show()
                        }
                    });
                }
            });
        },
        removedfile: function(file) {
            var name = file.name;
            var response = JSON.parse(file.xhr.response);

            $.ajax({
                type: 'POST',
                url: $('#imagebank-uploader').attr('action'),
                data: { file: response.name, action: 'remove_temp', _token: ANH.token },
                success: function(data) {

                    if (data == '1') {
                        toastr['success'](name + ' was deleted', 'SUCCESS');

                        var index = FILES.indexOf(response.name);
                        if (index > -1) {
                            FILES.splice(index, 1);
                        }

                        file.previewElement.remove();

                        initButtonUpload();
                    }

                },
                error: function() {
                    toastr['error']('An error occurred, please try again', 'ERROR');
                }
            });
        },
        success: function(file) {

            if (file) {
                var response = JSON.parse(file.xhr.response);

                if (response) {
                    FILES.push(response.name);

                    initButtonUpload();
                }
            }

            if (WEBCAM) {
                BUTTON_BROWS.html('<i class="fa fa-refresh fa-spin"></i>');
            } else if (ONLYUPLOAD) {
                BUTTON_BROWS.html('<i class="fa fa-refresh fa-spin"></i>');
            }

        },
        /*addedfile: function(file) {
            //trigger loading
            if (ONLYUPLOAD) {
                BUTTON_BROWS.html('<i class="fa fa-refresh fa-spin"></i> Uploading...');
            }
        }*/
    });

    $(document).on('click', '#imgbank-button-upload', function() {

        if (!FILES.length) {
            toastr['error']('There should be at least a file uploaded', 'ERROR');
        }

        var form = $('#imgbank-form-upload');

        if (!form.valid()) {
            toastr['error']('Please complete the required form', 'ERROR');
        }

        $('#imgbank-button-upload').attr('disabled', true);
        $('#imgbank-button-upload').html('<i class="fa fa-refresh fa-spin"></i> Uploading...');

        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: form.serialize() + '&files=' + JSON.stringify(FILES),
            success: function(dt) {
                if (dt.status) {
                    toastr['success'](dt.message, 'SUCCESS');

                    if (ANH.url.page == 'image-bank') {
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                    } else {
                        fetchImage(LAST_FETCH, true);

                        $('.dz-preview').remove();
                        $('.dz-message').css('display', 'table-cell');
                    }

                    // ...dispatch on elem!
                    document.dispatchEvent(new CustomEvent("uploaded", {
                        detail: dt
                    }));

                } else {
                    toastr['error'](dt.message, 'ERROR');
                }
                $('#imgbank-button-upload').attr('disabled', false);
                $('#imgbank-button-upload').html('<i class="fa fa-upload"></i> Upload');
            },
            error: function() {
                toastr['error']('An error occurred, please try again', 'ERROR');

                $('#imgbank-button-upload').attr('disabled', false);
                $('#imgbank-button-upload').html('<i class="fa fa-upload"></i> Upload');
            }
        })

        return false;
    });

    function initButtonUpload() {
        $('#imgbank-button-upload').attr('disabled', (FILES.length ? false : true));

        //trigger click
        if (ONLYUPLOAD) {
            $('#imgbank-button-upload').click();
        }
    }
}

//WIDGET IMAGE BANK
$(document).on('click', '.btn-img-brows', function() {

    BUTTON_BROWS = $(this);
    ONLYUPLOAD = BUTTON_BROWS.data('onlyupload') ? true : false;
    WEBCAM = BUTTON_BROWS.data('webcam') ? true : false;
    OLDVALUE = BUTTON_BROWS.html();

    const acceptedFiles = $(this).data('accept');
    const maxItem = $(this).data('max-item');
    const itemCount = $(this).closest('.list-attachments').find('table tbody .item').length;

    console.log(maxItem);

    // validate item
    if( maxItem && (maxItem <= itemCount) )
    {
        toastr['warning']('Attachment maximum '+ maxItem +' items', 'WARNING');
        return false;
    }

    // accepted file
    if( acceptedFiles )
    {
        $('.dz-hidden-input').attr('accept', acceptedFiles);
    }
    else
    {
        $('.dz-hidden-input').attr('accept', defaultAcceptedFile);
    }

    var inp = $('#imgbank-form-upload .input-title, #imgbank-form-upload .input-event,  #imgbank-form-upload .input-date,  #imgbank-form-upload .input-location, #imgbank-form-upload .input-keywords,  #imgbank-form-upload .input-photographer, #imgbank-form-upload .input-copyright');

    if (BUTTON_BROWS.data('key') && BUTTON_BROWS.data('input')) {
        $('#imgbank-form-upload input[name=title]').val(BUTTON_BROWS.data('key'));
        $('#imgbank-form-upload input[name=event]').val(BUTTON_BROWS.data('key'));
        $('#imgbank-form-upload input[name=location]').val(BUTTON_BROWS.data('key'));
        $('#imgbank-form-upload .caption label').html(BUTTON_BROWS.data('key'));
        $('#imgbank-form-upload input[name=keywords]').val(BUTTON_BROWS.data('key'));

        if (BUTTON_BROWS.data('value')) {
            $('#imgbank-form-upload .caption [name=caption]').html(BUTTON_BROWS.data('value'));

            //show hide
            $('#tab-img-upload .input-caption').hide();
            $('#tab-img-upload .input-photo').removeClass('col-md-7');
            $('#tab-img-upload .input-photo').addClass('col-md-12');
        } else {
            $('#tab-img-upload .input-caption').show();
            $('#tab-img-upload .input-photo').addClass('col-md-7');
            $('#tab-img-upload .input-photo').removeClass('col-md-12');
        }

        inp.hide();
    } else {
        inp.show();
    }

    //trigger click
    if (ONLYUPLOAD) {
        if (WEBCAM)
            $('#modal-webcam').modal('show');
        else
            $('#imagebank-uploader .needsclick:first').click();

        $('#modalImageBankPopup').modal('hide');
    } else $('#modalImageBankPopup').modal('show');

    return false;
});

if ($('#imagebank-list').length) {

    fetchImage(ANH.url.base + '/image-bank');

    $(document).on('click', '#imagebank-list a', function() {

        var href = $(this).attr('href');

        if (href == '#modalNewFolder') {
            window.open(ANH.url.base + '/image-bank', '_blank');
        } else if (href == '#modalUpload') {
            $('a[href="#tab-img-upload"]').click()
        } else if ($(this).hasClass('OpenModalPreview')) {

            var row = JSON.parse($(this).attr('data-row'));
            var url = $(this).attr('data-url');

            if (EDITOR_BROWS) {
                /*var content = '<div class="media">\
                                <img src="' + url + '"/>\
                                <p class="caption">' + row.caption + '</p>\
                            </div>';*/
                        var content = '<img src="' + url + '"/>';

                EDITOR_BROWS.invoke('editor.pasteHTML', content);

                EDITOR_BROWS = null;
            } else if (BUTTON_BROWS) {
                var parent = BUTTON_BROWS.closest('.widget-image');

                //preview
                parent.find('.widget-preview').attr('src', url);
                parent.find('.widget-value').val(row.path);
                parent.find('.widget-description').val(row.caption);

                //trigger loading
                BUTTON_BROWS.html(OLDVALUE);

                BUTTON_BROWS = null;
                OLDVALUE = null;
            }


            $('#modalImageBankPopup').modal('hide');
        } else {
            if (href != '#') fetchImage(href);
        }


        return false;
    });

}
//WIDGET IMAGE BANK


//AUDIO
var audiotypes = {
    "mp3": "audio/mpeg",
    "mp4": "audio/mp4",
    "ogg": "audio/ogg",
    "wav": "audio/wav",
    "m4r": "audio/m4r"
}

function ss_soundbits(sound) {
    var audio_element = document.createElement('audio')
    if (audio_element.canPlayType) {
        for (var i = 0; i < arguments.length; i++) {
            var source_element = document.createElement('source')
            source_element.setAttribute('src', arguments[i])
            if (arguments[i].match(/\.(\w+)$/i))
                source_element.setAttribute('type', audiotypes[RegExp.$1])
            audio_element.appendChild(source_element)
        }
        audio_element.load()
        audio_element.playclip = function() {
            audio_element.pause()
            audio_element.currentTime = 0
            audio_element.play()
        }
        return audio_element
    }
}

//WEBCAM
Webcam.set({
    width: 1280,
    height: 960,
    image_format: 'jpeg',
    jpeg_quality: 90
});
$("#modal-webcam").on('show.bs.modal', function() {
    $('#modalImageBankPopup').modal('hide');

    Webcam.reset();
    Webcam.attach('#camera-take-picture');

});
$("#modal-webcam").on('hide.bs.modal', function() {
    Webcam.reset();
});
$(document).on('click', '.btn-take-picture', function() {
    Webcam.freeze();
    $('#modal-webcam .save-camera').show();
    $('#modal-webcam .repeat-camera').show();
    $('#modal-webcam .btn-close').hide();
    $('#camera-take-picture video').hide();
    $(this).hide();
});
$(document).on('click', '#modal-webcam .repeat-camera', function() {

    Webcam.unfreeze();

    $('#modal-webcam .save-camera').hide();
    $('#modal-webcam .repeat-camera').hide();
    $('#modal-webcam .btn-close').show();
    $('#modal-webcam .btn-take-picture').show();

    $('#camera-take-picture canvas').hide();
    $('#camera-take-picture video').show();
});

$(document).on('click', '#modal-webcam .save-camera', function() {
    Webcam.snap(function(data_uri) {

        $('#modal-webcam .save-camera').attr('disabled', true);
        $('#modal-webcam .save-camera').html('<i class="fa fa-refresh fa-spin"></i> Menyimpan...');

        $.ajax({
            type: 'POST',
            url: ANH.url.base + '/image-bank?action=upload_camera',
            data: 'image=' + data_uri + '&_token=' + ANH.token,
            success: function(dt) {

            },
            error: function(xhr, ajaxOptions, thrownError) {
                toastr['error'](thrownError, 'ERROR');
            }
        }).done(function(dt) {
            FILES.push(dt.name);
            $('#imgbank-button-upload').attr('disabled', false);
            $('#modal-webcam .save-camera').attr('disabled', false);
            $('#modal-webcam .save-camera').html('Simpan');
            $('#imgbank-button-upload').click();
            $('#modal-webcam').modal('hide');
        });

        Webcam.reset();
    });
});

//AUDIO
window.notifAudio = ss_soundbits(
    ANH.url.asset + '/audio/plucky.ogg',
    ANH.url.asset + '/audio/plucky.mp3',
    ANH.url.asset + '/audio/plucky.m4r');