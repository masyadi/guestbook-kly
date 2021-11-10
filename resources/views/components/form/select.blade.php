<div class="form-group">
    <label>{{ __( ucwords( str_replace('_',' ',$label??$name)) ) }}</label>
    <select 
        
        class="{{ $class ?? 'select2 form-control' }}" 
        name="{{$name}}" 
        
        {!! $attribute??'' !!}
    >
    
    {!! $labelOption ?? '' !!}

    @if( isset($values) && $values )
    @foreach( $values as $valid=>$val )
    	<option value="{{$valid}}" 
        
        @if( preg_match('/\[\]$/', $name) )
            {!! in_array($valid, old($name, (isset($row->{$name}) && $row->{$name} ? $row->{$name} : (isset($selected) ? $selected : [])) ))?'selected="selected"':'' !!}" 
        @else
            {!! old($name, (isset($row->{$name}) && $row->{$name} ? $row->{$name} : (isset($selected) ? $selected : '')) )==$valid?'selected="selected"':'' !!}" 
        @endif

        >{{ $val }}</option>
    @endforeach
    @endif
	</select>
</div>