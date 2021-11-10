@php

    // $val = old($name, (isset($row->{$name}) ? $row->{$name} : ($name_default??'')) );
    $val = old($name, (isset($row->{$name}) ? $row->{$name} : ($imageSource??'')) );
    
@endphp
<div class="hpanel widget-image">
    <div class="panel-heading">
        <div class="panel-tools">
            <a href="#" data-toggle="tooltip" class="btn btn-info btn-img-brows" data-onlyupload="{{$onlyupload??''}}" data-input="{{$hideinput??''}}" data-key="{{$desc_placeholder??($dataValue??'')}}" data-value="{{$dataValue??''}}" style="margin: -10px -5px; color: #fff" title="{{__('Upload')}}"><i class="fa fa-upload"></i></a>
            @if(isset($webcam))
            <a href="#" data-toggle="tooltip" class="btn btn-info btn-img-brows" data-onlyupload="{{$onlyupload??''}}" data-webcam="1" data-input="{{$hideinput??''}}" data-key="{{$desc_placeholder??($dataValue??'')}}" data-value="{{$dataValue??''}}" style="margin: -10px -5px -10px 5px; color: #fff" title="{{__('Kamera')}}"><i class="fa fa-camera"></i></a>
            @endif
        </div>
        {{ __( ucwords( str_replace('_',' ',$label??$name)) ) }}
    </div>
    <div class="panel-body no-padding">
        <img src="{{ $val ? Storage::url($val) : asset('static/png/no-image.png')}}" class="widget-preview" style="width: 100%;" />
    </div>

    @if( isset($description) )
    <div class="panel-footer">
        <input type="text" 
            name="{{ $description }}" 
            placeholder="{{ $desc_placeholder ?? __('Image description') }}" 
            class="form-control widget-description" 
            value="{{ old($description, (isset($row->{$description}) ? $row->{$description} : ($description_default??'')) ) }}"
            />
    </div>
    @endif

    <input type="hidden" 
            name="{{ $name }}" 
            class="form-control widget-value" 
            value="{{ $val }}" />
</div>