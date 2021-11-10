@extends('CMS::layouts.main')

@section('content')

<div class="text-right m-b-md">
    <a href="{{url('notif?read=1')}}">{{__('Tandai semua sudah dibaca')}}</a>
</div>

<div class="hpanel animated-panel zoomIn">
    <div class="panel-body">
        <table class="table table-striped table-bordered table-hover datatable">
            <thead>
                <tr>
                    <th data-data="created_at" class="fit">{{__('Date')}}</th>
                    <th data-data="data">{{__('Activity')}}</th>
                </tr>
            </thead>
        </table>   
    </div>
</div>
@endsection