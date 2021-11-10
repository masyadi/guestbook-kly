@extends('CMS::layouts.index')

@section('index')
<table class="table table-striped table-bordered table-hover datatable">
    <thead>
        <tr>
            <th data-data="id" class="fit text-center">{{__('ID')}}</th>
            <th data-data="path">{{__('Path')}}</th>
            <th data-data="status" class="fit">{{__('Status')}}</th>
            <th data-data="created_at" class="fit">{{__('Created at')}}</th>
            <th data-data="action" orderable="false" class="fit">{{__('Action')}}</th>
        </tr>
    </thead>
</table>    
@endsection