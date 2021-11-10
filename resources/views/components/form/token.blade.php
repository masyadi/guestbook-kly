<div class="form-group">
    <label>{{ __( ucwords( str_replace('_',' ',$label??$name)) ) }}</label>
    <input type="text" 
        
        class="{{ $class ?? 'form-control input-token' }}" 
        name="{{$name}}" 
        data-url="{{$url}}" 
        data-max="{{$max??''}}" 
        data-target="{{$target??''}}" 
        data-onadd="{{$onAdd??''}}" 
        data-ondelete="{{$onDelete??''}}" 
        data-format="{{$onFormat??''}}" 

        @if( isset($model) )
        value="{{ Helper::formatTokenInput(
            $model, old($name, (isset($row->{$name}) ? $row->{$name} : ($default??null)) ), ($field??'name') 
        , ($formatter??'')) }}"
        @endif
        
        {!! $attribute??'' !!}
    />
</div>