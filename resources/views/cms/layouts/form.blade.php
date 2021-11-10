@extends('CMS::layouts.main')

@section('content')

<div class="hpanel animated-panel {{config('display.effect')}}">
    {{-- <div class="panel-body panel-input"> --}}
    <div class="panel-body">
    
        <fieldset>
            
            <form method="POST" action="{{ Helper::CMS(Helper::page()) }}" enctype="multipart/form-data" class="validate-form">

            <!-- Disable auto save  -->
            <!-- <span class="autosave" data-interval="5"></span> -->
            
            @if ( isset($row->id) && $row->id )
            <legend>
                <i class="fa fa-save"></i> {{__('Formulir Edit')}}
            </legend>
            <input type="hidden" name="id" value="{{ $row->id }}" />
            @else
            <legend>
                <i class="fa fa-plus"></i> {{__('Formulir Tambah')}}
            </legend>
            @endif
            
        
                @yield('form')
    
                <div class="hr-line-dashed"></div>
                
                <div class="form-group">
                    <a class="btn btn-default" href="{{ $urlBack ?? Helper::CMS(Helper::page().(config('_redirect_params') ? '?'.http_build_query(config('_redirect_params')):'')) }}"> 
                        {!! isset($labelBack) ? $labelBack : '<i class="fa fa-close"></i> '.__('Batal') !!}
                    </a>
                    <a class="btn btn-default" href="{{ Helper::CMS('remote?act=reset-autosave') }}"><i class="fa fa-refresh"></i> {{__('Reset')}}</a>
                    <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> {{__('Simpan')}}</button>
                </div>

                <span class="required">(*)</span> wajib diisi
                
                @csrf()

                @if( $queries = config('_redirect_params') )
                @foreach( $queries as $k_q=>$v_q )
                    <input type="hidden" name="_redirect_params[{{$k_q}}]" value="{{$v_q}}">
                @endforeach
                @endif
                

            </form>
                
        </fieldset>
        
        
    </div>
</div>
@endsection

@section('button_top_right')
    @yield('add_button_top_right')
    {{-- <a href="{{ Helper::CMS(Helper::page().(config('_redirect_params') ? '?'.http_build_query(config('_redirect_params')):'')) }}" class="btn btn-default" data-toggle="tooltip" title="{{__('Kembali')}}" data-placement="bottom"><i class="fa fa-chevron-left"></i> <span class="hidden-xs">{{ __('Kembali') }}</span></a> --}}
    <button onclick="history.back();" class="btn btn-default" data-toggle="tooltip" title="{{__('Kembali')}}" data-placement="bottom"><i class="fa fa-chevron-left"></i> <span class="hidden-xs">{{ __('Kembali') }}</span></button>
@endsection