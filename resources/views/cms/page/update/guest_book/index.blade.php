@extends('CMS::layouts.index')

@section('index')
<table class="table table-striped table-bordered table-hover datatable">
    <thead>
        <tr>
            <th data-data="first_name">{{__('First Name')}}</th>
            <th data-data="last_name">{{__('Last Name')}}</th>
            <th data-data="organization">{{__('Organization')}}</th>
            <th data-data="address_label">{{__('Address')}}</th>
            <th data-data="updated_at" class="fit">{{__('Last Update')}}</th>
            <th data-data="status" class="text-center fit">{{__('Status')}}</th>
            <th data-data="action" orderable="false" class="fit">{{__('Action')}}</th>
        </tr>
    </thead>
</table>    
@endsection