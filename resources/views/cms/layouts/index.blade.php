@extends('CMS::layouts.main')

@section('content')

@yield('index_header')

<div class="hpanel animated-panel {{config('display.effect')}}">
    <div class="panel-body">
        @yield('index')
    </div>
</div>
    
@endsection

@section('button_top_right')
    <a href="{{ Helper::CMS(Helper::page().'/create'.(config('_redirect_params') ? '?'.http_build_query(config('_redirect_params')):'')) }}" class="btn btn-primary" data-toggle="tooltip" title="{{__('Tambah Baru')}}" data-placement="bottom"><i class="fa fa-plus"></i> <span class="hidden-xs">{{ __('Tambah Baru') }}</span></a>
    <a href="{{ Helper::CMS(Helper::page().'?export=1'.(config('_redirect_params') ? '&'.http_build_query(config('_redirect_params')):'')) }}" id="table-print" class="btn btn-success" data-toggle="tooltip" title="{{__('Cetak')}}" data-placement="bottom"><i class="fa fa-print"></i> <span class="hidden-xs">{{ __('Cetak') }}</span></a>
    @yield('button_import')
@endsection