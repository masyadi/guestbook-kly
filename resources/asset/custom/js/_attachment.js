if( $('.list-attachments').length )
{
    var ATTACH_TABLE = null;
    var ATTACH_BUTTON = null;
    var INPUT_NAME = null;

    $(document).on('click', '.btn-add', function(){
        
        ATTACH_BUTTON = $(this);
        
        INPUT_NAME = $(this).data('name');

        ATTACH_TABLE = $(this).closest('.list-attachments').find('table');

        ATTACH_TABLE.find('.loader').remove();

        const itemCount = $(ATTACH_TABLE).find('tbody tr').not('.empty').length;

        if( $(this).data('max-item') <= itemCount )
        {
            $('#modalImageBankPopup').modal('hide');
            return true;
        }

        var tr = `<tr class="loader">
                    <td colspan="3" class="text-center" style="padding: 15px 0;">
                        ${ANH.loading.icon}
                    </td>
                  </tr>`;

        ATTACH_TABLE.find('.empty').before(tr);

        initEmpty();

        return false;
    });

    document.addEventListener("uploaded", function(event) {

        if( event.detail.file && ATTACH_BUTTON )
        {
            var oldTr = null;
            var desc = event.detail.file.info;
            var mimeType = event.detail.file.mime_type;
            var tr = null;

            const titleName = INPUT_NAME ? `attachment[${INPUT_NAME}][${event.detail.file.id}][title]` : `attachment[${event.detail.file.id}][title]`;
            const ParamsUrl = INPUT_NAME ? `attachment[${INPUT_NAME}][${event.detail.file.id}][params][url]` : `attachment[${event.detail.file.id}][params][url]`;
            const pathName = INPUT_NAME ? `attachment[${INPUT_NAME}][${event.detail.file.id}][path]` : `attachment[${event.detail.file.id}][path]`;

            oldTr = ATTACH_BUTTON.closest('tr');
            if( oldTr.find('textarea').length )
            {   
                desc = oldTr.find('textarea').val();
            }

            if( mimeType.match(/^image\//g) )
            {
                

                tr = `<tr class="item">
                    <td class="text-center">
                        <img src="${event.detail.file.thumb}" is-image="${event.detail.file.is_image}" data-src="${event.detail.file.real}" class="widget-preview" style="max-width: 100%;min-width: 48px;cursor: pointer;"/>
                        <textarea class="form-control" maxlength="255" name="${titleName}" style="min-height: 48px;" title="Keterangan" placeholder="Keterangan">${desc}</textarea>
                        <input class="form-control" maxlength="255" name="${ParamsUrl}" title="URL" placeholder="URL">
                        <input type="hidden" name="${pathName}" value="${event.detail.file.path}"/>
                    </td>
                    <td class="fit text-center">
                        <a href="#" title="Hapus item" class="btn btn-default btn-del btn-xs"><i class="fa fa-close text-danger"></i></a>
                        <br/><br/>
                        <a href="#" title="Upload item" class="btn btn-default btn-upload btn-xs btn-img-brows" data-onlyupload="1" data-input="1" data-key="attachment" data-value="attachment"><i class="fa fa-upload text-info"></i></a>
                    </td>
                </tr>`;
            }
            else
            {
                tr = `<tr class="item">
                    <td class="text-center">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-file"></i></div>
                            <input type="text" name="${titleName}" value="${desc}" class="form-control" />
                        </div>
                        <input type="hidden" name="${pathName}" value="${event.detail.file.path}" />
                    </td>
                    <td class="fit text-center">
                        <a href="#" title="Hapus item" class="btn btn-default btn-del btn-xs" style="margin-bottom: 5px"><i class="fa fa-close text-danger"></i></a>
                        <br/>
                        <a href="#" title="Upload item" class="btn btn-default btn-upload btn-xs btn-img-brows" data-onlyupload="1" data-input="1" data-key="attachment" data-value="attachment" style="margin-bottom: 5px"><i class="fa fa-upload text-info"></i></a>
                        <br>
                        <a href="${event.detail.file.real}" target="__blank" title="Download item" class="btn btn-default btn-xs" style="margin-bottom: 5px"><i class="fa fa-download text-success"></i></a>
                    </td>
                </tr>`;
            }

            if(ATTACH_TABLE && ATTACH_TABLE.find('.loader').length) ATTACH_TABLE.find('.loader').remove();

            if( $(ATTACH_BUTTON).hasClass('btn-upload') )
            {
                oldTr.after(tr);
                oldTr.remove();
                
                oldTr = null;
            }
            else if( $(ATTACH_BUTTON).hasClass('btn-add') )
            {
                ATTACH_TABLE.find('.empty').before(tr);

                setTimeout(function(){
                    ATTACH_TABLE.closest('.list-attachments').find('.panel-heading .btn-img-brows').html('<i class="fa fa-plus"></i>');
                    ATTACH_TABLE.closest('.list-attachments').find('.empty .btn-img-brows').html('tambah');
                },2000);
            }

            ATTACH_BUTTON = null;
        }
    });

    $(document).on('click', '.list-attachments .widget-preview', function(){
        var isImage = $(this).attr('is-image');
        var src = $(this).attr('data-src');

        if( isImage=='true' )
        {
            $('#attachmentPreview').modal('show');
            $('#attachmentPreview img').attr('src', src);
        }
        else
        {
            var a = document.createElement("a");
            a.href = src;
            a.target = "_blank";
            a.click();
            a.remove();
        }

        return false;
    });

    $(document).on('click', '.btn-del', function(){
        
        $(this).closest('tr').remove();
        initEmpty();

        return false;
    });

    $(document).on('click', '.btn-upload', function(){
        
        ATTACH_BUTTON = $(this);

        ATTACH_TABLE = $(this).closest('.list-attachments').find('table');
        
        return false;
    });

    initEmpty();

    function initEmpty()
    {
        $('.list-attachments').each(function(){
            var tb = $(this).find('table');
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
}