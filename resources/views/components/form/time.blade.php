
<div class="form-group input-{{$name}}">
    <label>{{ __( ucwords( str_replace('_',' ',$label??$name)) ) }}</label>
    <input type="time" 
        
        class="{{ $class ?? 'form-control' }}" 
        name="{{$name}}" 
        value="{{ old($name, (isset($row->{$name}) ? $row->{$name} : ($default??''))) }}"
        
        {!! $attribute??'' !!}
    />
</div>