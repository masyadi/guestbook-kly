@extends('CMS::layouts.index')

@section('index')
<table class="table table-striped table-bordered table-hover datatable">
    <thead>
        <tr>
            <th data-data="kode_rs" class="fit">{{__('Kode Rumah Sakit')}}</th>
            <th data-data="name">{{__('Nama Rumah Sakit')}}</th>
            <th data-data="judul">{{__('Judul Aplikasi')}}</th>
            <th data-data="status" class="text-center fit">{{__('Status')}}</th>
            <th data-data="updated_at" class="fit">{{__('Last Update')}}</th>
            <th data-data="action" orderable="false" class="fit">{{__('Action')}}</th>
        </tr>
    </thead>
</table>    
@endsection