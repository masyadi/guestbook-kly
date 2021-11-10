    <button 
    type="{{ $type ?? 'button' }}" 
    class="{{ $class ?? 'btn btn-primary' }}" 
    name="{{ $name ?? '' }}"
    {!! $attributes??'' !!}
    >
    {!! __($label ?? 'Button') !!}
    </button>