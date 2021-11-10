@extends('CMS::layouts.main')

@section('content')

<div class="alert alert-warning alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <strong>PENTING!!!</strong> Mohon download terlebih dahulu format yang akan digunakan.
  </div>

<div class="row">
    <div class="col-md-6">
        <fieldset>
            <legend>{{__('Master Data')}}</legend>
            <div class="hpanel animated-panel {{config('display.effect')}}">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-condensed table-bordered table-hover" style="margin-bottom: 0;">
                            <tbody>
                                @foreach( [
                                    'blog'=> 'Blog',
                                    'page'=> 'Page',
                                    'tag'=> 'Tag',
                                ] as $route=>$title )
                                @php
                                 $header = [
                                    'title'=> 'Judul '.$title,
                                ];

                                if( $route =='blog' )
                                {
                                    $header['category_id'] = 'ID Kategori';
                                    $header['content'] = 'Isi';
                                }
                                elseif(  $route=='page' )
                                {
                                    $header['content'] = 'Isi';
                                }
                                elseif(  $route=='tag' )
                                {
                                    $header['type'] = 'Tipe';
                                }
                                

                                $params = [
                                    'export'=>1,
                                    'route'=>$route,
                                    'noheader'=>1,
                                    'header'=> $header
                                 ]
                                @endphp
                                <tr>
                                    <th colspan="2" class="text-center">{{strtoupper($title)}}</th>
                                </tr>
                                <tr>
                                    <td class="fit"><a href="{{ url($route.'?'.http_build_query($params)) }}" class="btn btn-info btn-sm"><i class="fa fa-download"></i> {{__('Download')}}</a></td>
                                    <td>
                                        <form method="POST" enctype="multipart/form-data" action="{{ url(Helper::page().'?doupload=1&'.http_build_query($params)) }}" class="validate-form">
                                            @csrf
                                            <div class="input-group">
                                                <input type="file" name="file" id="file_{{$route}}" class="form-control" accept=".xlsx" required> 
                                                <span class="input-group-btn"> 
                                                    <button type="submit" class="btn btn-info btn-sm" data-label="Uploading..."><i class="fa fa-upload"></i> {{__('Upload')}}</button>
                                                </span>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-center">&nbsp;</th>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</div>

    
@endsection