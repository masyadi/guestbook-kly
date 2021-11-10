<div class="container my-5">
    <div class="d-flex justify-content-center text-center">
        <div class="card-body">
            <h5 class="card-title">{{ $title ?? __('pages.no.data.title') }}</h5>
            <p class="card-text">{{ $description ?? __('pages.no.data.description') }}</p>
            
            @if (isset($action))
                {!! $action !!}
            @else
                <a href="{{ url('/') }}" class="btn btn-primary">{{ __('pages.no.data.button') }}</a>
            @endif
        </div>
    </div>
</div>