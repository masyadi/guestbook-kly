<div class="form-group">
    <label>{{ __( ucwords( str_replace('_',' ',$label??$name)) ) }}</label>
    <textarea
        
        class="{{ $class ?? 'form-control' }}" 
        name="{{$name}}" 
        
        {!! $attribute??'' !!}

    >{!! old($name, (isset($row->{$name}) ? $row->{$name} : ($default??'')) ) !!}</textarea>
</div>