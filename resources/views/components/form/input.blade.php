<div>
    <div class="form-group input-{{$name}}">
        <label>{{ __( ucwords( str_replace('_',' ',$label??$name)) ) }}</label>
        <input type="{{ $type ?? 'text' }}" 
            
            class="{{ $class ?? 'form-control' }}" 
            name="{{$name}}" 
    
            @if( ($type ?? 'text') !='password' )
            value="{{ old($name, (isset($row->{$name}) ? $row->{$name} : ($default??'')) ) }}"
            @endif
            
            @if( isset($attribute) ) 
            {!! $attribute??'' !!}
            @else
            {!! $attributes??'' !!}
            @endif
        />
    </div>
</div>