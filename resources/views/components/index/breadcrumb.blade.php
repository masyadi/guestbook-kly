<nav aria-label="breadcrumb">
    <ol class="breadcrumb justify-content-center">
        <li class="breadcrumb-item">
            <i class="fas fa-home"></i> <a href="{{ url('/') }}">Home</a>
        </li>
        @if (isset($pages))
            @if (is_array($pages))
                @foreach ($pages as $item)
                    <li class="breadcrumb-item {{ $loop->last ? 'active' : null }}">
                        @if (isset($item['url']) || isset($item['title']))
                            @if ($loop->last)
                            {{ __($item['title'] ?? '') }}
                            @else
                                <a href="{{ $item['url'] ?? '#' }}">{{ __($item['title'] ?? '') }}</a>
                            @endif
                        @else
                            {{ __($item) }}
                        @endif
                    </li>
                @endforeach
            @else
                <li class="breadcrumb-item active">
                    {{ __($pages) }}
                </li>
            @endif
        @else
            <?php $segments = url('/'); ?>
            @foreach(Request::segments() as $segment)
                <?php $segments .= '/'.$segment; ?>
                <li class="breadcrumb-item {{ $loop->last ? 'active' : null }}">
                    @if ($loop->last)
                        {{ ucwords(__($segment)) }}
                    @else
                        <a href="{{ $segments }}">{{ ucwords(__($segment)) }}</a>
                    @endif
                </li>
            @endforeach
        @endif
    </ol>
</nav>