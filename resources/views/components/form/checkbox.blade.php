<div class="form-group">
    <div>
    	<label> 
    		<input type="checkbox" 
    			name="{{$name}}" 
    			class="{{ $class ?? 'i-checks' }}" 
    			value="1"
    			{{ old($name, (isset($row->{$name}) && $row->{$name}) ? $row->{$name} : '0' ) == '1' ? 'checked="checked"' : '' }}
    			{!! $attribute??'' !!}
    		> 
    		{!! __( ucwords( str_replace('_',' ',$label??$name)) ) !!}
    	</label>
        <label id="{{$name}}-error" class="error" for="{{$name}}" style="display: none;"></label>
    </div>
</div>

