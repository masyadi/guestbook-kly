@extends('CMS::layouts.main')

@section('content')

<div class="row">
    <div class="col-lg-12 text-center welcome-message" style="margin-bottom: 20px">
        <h2>
            {{__('Welcome to')}} {{config('app.name')}}
        </h2>
    </div>
</div>
<div class="row">



    @if( Session::get('menu.slug_id.blog') )
    <div class="col-md-3">
        <div class="hpanel animated-panel zoomIn">
            <div class="panel-body">
                <div class="stats-title pull-left">
                    <h4>{{__('Total Blog')}}</h4>
                </div>
                <div class="m-t-xl">
                    <h1 class="text-info text-center total-blog">0</h1>
                </div>
            </div>
            <div class="panel-footer text-right">
                <a href="{{ url('blog') }}">{{__('Selengkapnya')}} <i class="fa fa-chevron-right"></i></a>
            </div>
        </div>
    </div>
    @endif

    @if( Session::get('menu.slug_id.page') )
    <div class="col-md-3">
        <div class="hpanel animated-panel zoomIn">
            <div class="panel-body">
                <div class="stats-title pull-left">
                    <h4>{{__('Total Page')}}</h4>
                </div>
                <div class="m-t-xl">
                    <h1 class="text-info text-center total-page">0</h1>
                </div>
            </div>
            <div class="panel-footer text-right">
                <a href="{{ url('page') }}">{{__('Selengkapnya')}} <i class="fa fa-chevron-right"></i></a>
            </div>
        </div>
    </div>
    @endif


</div>


@if( Session::get('menu.slug_id.blog') )
<div class="row">
    <div class="col-md-12">
        <div class="hpanel animated-panel zoomIn">
            <div class="panel-heading">
                <h4>{{__('Blog Terbaru')}}</h4>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table data-blog">
                        <thead>
                            <th>{{__('Judul')}}</th>
                            <th class="fit">{{__('Category')}}</th>
                            <th class="fit">{{__('Author')}}</th>
                            <th class="fit">{{__('Tanggal Publish')}}</th>
                            <th class="fit"></th>
                        </thead>
                        <tbody>
                            <td colspan="4" class="text-center">{{__('Belum ada blog')}}</td>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-footer text-right">
                <a href="{{ url('blog') }}">{{__('Lihat semuanya di Update Blog')}} <i class="fa fa-chevron-right"></i></a>
            </div>
        </div>
    </div>
</div>
@endif

@if( Session::get('menu.slug_id.page') )
<div class="row">
    <div class="col-md-12">
        <div class="hpanel animated-panel zoomIn">
            <div class="panel-heading">
                <h4>{{__('Halaman Terbaru')}}</h4>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table data-page">
                        <thead>
                            <th>{{__('Judul')}}</th>
                            <th class="fit">{{__('Author')}}</th>
                            <th class="fit"></th>
                        </thead>
                        <tbody>
                            <td colspan="4" class="text-center">{{__('Belum ada halaman')}}</td>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-footer text-right">
                <a href="{{ url('page') }}">{{__('Lihat semuanya di Update Page')}} <i class="fa fa-chevron-right"></i></a>
            </div>
        </div>
    </div>
</div>
@endif

@endsection