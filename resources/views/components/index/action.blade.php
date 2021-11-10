<div class="btn-action nowrap text-center">
    @if( isset($row->company_id) && $row->company_id==0 && session('user.relrole.slug')!='super-admin' )
        <button type="button" class="btn btn-sm btn-default" disabled data-toggle="tooltip" title="{{__('Edit')}}"> <i class="fa fa-pencil"></i> </button>
        <button type="button" class="btn btn-sm btn-default action-del text-danger" disabled data-toggle="tooltip" title="{{__('Delete')}}"> <i class="fa fa-trash"></i> </button>
    @else

        {!! $action??'' !!}

    @endif
</div>