<div class="form-group">
    <label>{{ __( ucwords( str_replace('_',' ',$label??$name)) ) }}</label>
    <div>
    @if( isset($values) && $values )
    @foreach( $values as $valid=>$val )

            <label style="margin: 0 10px"> 
                <input 
                type="radio" 
                class="{{ $class ?? 'i-checks' }}" 
                name="{{$name}}" 
                {!! $attribute??'' !!}
                value="{{$valid}}" {!! old($name, (isset($row->{$name}) && $row->{$name} ? $row->{$name} : (isset($checked) ? $checked : [])) )==$valid?'checked="checked"':'' !!}> {{ $val }}</label>

    @endforeach
    @endif
    </div>
</div>