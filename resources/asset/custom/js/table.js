//init Data Table
if ($('.datatable').length) {

    $('.datatable').each(function() {

        var cols = [];

        $(this).find('thead th').each(function() {
            cols.push({
                data: $(this).data('data'),
                name: $(this).data('name') || $(this).data('data'),
                orderable: $(this).attr('orderable') == "false" ? false : true,
                searchable: $(this).attr('searchable') == "false" ? false : true,
                className: $(this).attr('class') || "",
            })
        });
        
        // hide or show searching
        let searching = $(this).data('datatable-searching');
        searching = typeof searching === typeof undefined ? true : searching;

        // hide or show lengthChange
        let lengthChange = $(this).data('datatable-length-change');
        lengthChange = typeof lengthChange === typeof undefined ? true : lengthChange;

        var tb = $(this).DataTable({
            order: [
                [0, "desc"]
            ],
            serverSide: true,
            processing: true,
            fixedHeader: true,
            responsive: true,
            stateSave: true,
            searching: searching,
            lengthChange: lengthChange,
            ajax: $(this).data('url') || "",
            columns: cols,
            "drawCallback": function(settings) {
                $('[data-toggle="tooltip"]').tooltip()
            }
        });

        tb.on('xhr.dt', function(e, settings, json, xhr) {
            if (json.total) {
                //render total
                for (var i in json.total) {
                    if ($('.total-' + i).length) $('.total-' + i).html(json.total[i])
                }
            }
        });

        $(document).on('click', '#table-print', function() {
            var params = tb.ajax.params();
            params._token = ANH.token;
            params.source = $(this).attr('href');

            $.post(ANH.url.web + '/remote?act=generate-report', params, function(data, textStatus, request) {
                window.location.href = data;
            });

            return false;
        })

    });
}

$(document).on('click', '.action-del', function() {

    swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this resource!",
            icon: "warning",
            buttons: {
                cancel: "Cancel",
                catch: {
                    text: "Delete",
                    closeModal: false,
                }
            },
        })
        .then((willDelete) => {

            if (willDelete) {
                $.ajax({
                    type: 'DELETE',
                    data: '_token=' + ANH.token,
                    url: $(this).attr('href'),
                    success: function(dt) {
                        if (dt.status) {
                            toastr['success'](dt.message, 'SUCCESS');

                            if ($('.datatable').length) {
                                $('.datatable').each(function() {
                                    $(this).DataTable().ajax.reload();
                                });
                            } else window.location.reload();
                        } else {
                            toastr['error'](dt.message, 'ERROR');
                        }

                        swal.stopLoading();
                        swal.close();
                    },
                    error: function(e) {
                        swal("Delete operation on resource has failed.", { icon: "error" });
                        swal.stopLoading();
                        swal.close();
                    }
                })
            }

        });


    return false;
});

if( $('.table-items').length )
{
    $(document).on('click', '.table-items .btn-add-item', function(){
        var nm = $(this).closest('.table').attr('name') || 'items';
        var id = 'new-'+Math.random().toString(36).substring(10);
        var tr = `<tr class="item">
                    <td>
                        <input name="items[`+nm+`][`+id+`][key]" class="form-control"/>
                    </td>
                    <td>
                        <input name="items[`+nm+`][`+id+`][value]" class="form-control"/>
                    </td>
                    <td class="fit">
                        <a href="#" class="btn btn-sm btn-default btn-add-item" title="Tambah setelah ini" data-toggle="tooltip" data-placement="left"><i class="fa fa-plus"></i></a>
                        <a href="#" class="btn btn-sm btn-default btn-up" title="Pindah item ini keatas" data-toggle="tooltip" data-placement="left"><i class="fa fa-arrow-up"></i></a>
                        <a href="#" class="btn btn-sm btn-default btn-down" title="Pindah item ini kebawah" data-toggle="tooltip" data-placement="left"><i class="fa fa-arrow-down"></i></a>
                        <a href="#" class="btn btn-sm btn-default btn-del" title="Hapus item ini" data-toggle="tooltip" data-placement="left"><i class="fa fa-close"></i></a>
                    </td>
                  </tr>
                 `;

        if( $(this).closest('tr').hasClass('item') )
        {
            $(this).closest('tr').after(tr);
        }
        else
        {
            $(this).closest('.table-items').find('tbody').append(tr);
        }
        
        if ($('[data-toggle="tooltip"]').length) $('[data-toggle="tooltip"]').tooltip()

        return false
    })

    $(document).on('click', '.table-items .btn-del', function(){
        
        $(this).closest('tr').remove()

        return false
    })
    $(document).on('click', '.table-items .btn-up', function(){
        
        var prev = $(this).closest('tr').prev();

        if( prev.hasClass('item') )
        {
            prev.before(prev.next());
        }

        return false
    })
    $(document).on('click', '.table-items .btn-down', function(){
        
        var next = $(this).closest('tr').next();

        if( next.hasClass('item') )
        {
            next.after(next.prev());
        }

        return false
    })

}