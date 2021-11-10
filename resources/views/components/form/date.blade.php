
<div class="form-group input-{{$name}}">
    <label>{{ __( ucwords( str_replace('_',' ',$label??$name)) ) }}</label>
    <input type="text" 
        
        class="{{ $class ?? 'form-control tDate' }}" 
        name="{{$name}}" 
        value="{{ old($name, (isset($row->{$name}) ? date('Y/m/d',strtotime($row->{$name})) : ($default??'') ) ) }}"
        
        {!! $attribute??'' !!}
        
        @if (isset($minDate))
            {{ 'data-min-date='. date('Y/m/d',  strtotime($minDate)) }}
        @endif
    />
</div>