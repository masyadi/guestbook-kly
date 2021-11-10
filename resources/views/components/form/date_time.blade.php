<div class="form-group input-{{$name}}">
    <label>{{ __( ucwords( str_replace('_',' ',$label??$name)) ) }}</label>
    <input type="text" 
        
        class="{{ $class ?? 'form-control tDateTime' }}" 
        name="{{$name}}" 
        value="{{ old($name, (isset($row->{$name}) ? date('Y/m/d H:i',strtotime($row->{$name})) : ($default??'') ) ) }}"
        
        {!! $attribute??'' !!}
    />
</div>