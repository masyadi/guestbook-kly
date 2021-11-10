@extends('CMS::layouts.index')

@section('index')
<table class="table table-striped table-bordered table-hover datatable">
    <thead>
        <tr>
            <th data-data="id" class="fit text-center">{{__('ID')}}</th>
            <th data-data="name">{{__('Name')}}</th>
            <th data-data="email">{{__('Email')}}</th>
            <th data-data="relrole.name" class="fit">{{__('Role')}}</th>
            <th data-data="status" class="fit">{{__('Status')}}</th>
            <th data-data="logged_in_at" class="fit">{{__('Last Login')}}</th>
            <th data-data="action" orderable="false" class="fit">{{__('Action')}}</th>
        </tr>
    </thead>
</table>    
@endsection