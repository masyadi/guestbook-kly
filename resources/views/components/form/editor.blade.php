<div class="form-group">
    <label>{{ __( ucwords( str_replace('_',' ',$label??$name)) ) }}</label>
    <textarea class="{{ $class ?? 'editor' }}" name="{{$name}}" 
        {!! $mode??'' !!}
        {!! $attribute??'' !!}>{!! old($name, (isset($row->{$name}) ? $row->{$name} : ($default??'')) ) !!}</textarea>
</div>