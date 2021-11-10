<!doctype html>
<html lang="{{App::getLocale()}}">

    <head>
        <title>{{ $title ?? __('Home') }} - {{ config('app.name') }}</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="{!! ($meta['description'] ?? 'Homepage') !!} {{ config('app.name') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
        <script src="https://kit.fontawesome.com/da2a2d005a.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/jquery.fancybox.css?v=2.1.5') }}" media="screen" />
        <link href="{{ asset('assets/css/hover-navbar.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset(mix('static/css/web.css')) }}">
    </head>
    
    <body>

        <div class="container">
            <div class="row">
                <div class="col">
                    <nav class="navbar navbar-expand-lg navbar-light btco-hover-menu">
                        <a class="navbar-brand mt-2" href="{{ url('/') }}">
                            <h5><b>Project Test</b></h5>
                        </a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarNavDropdown">
                            <ul class="navbar-nav mr-auto">
                                <li class="nav-item mr-2 {{ Helper::activeMenu('/') }}">
                                    <a class="nav-link" href="{{ url('/') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="nav-item mr-2 {{ Helper::activeMenu('guest-book') }}">
                                    <a class="nav-link" href="{{ url('guest-book') }}">{{ __('Guest Book') }}</a>
                                </li>
                          </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
        <hr>

        @yield('content')

        @include('const')
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
        <script src="{{ asset('assets/js/hover-navbar.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/js/jquery.fancybox.js') }}"></script>
        <script src="{{ asset(mix('static/js/manifest.js')) }}"></script>
        <script src="{{ asset(mix('static/js/vendor.js')) }}"></script>
        <script src="{{ asset(mix('static/js/web.js')) }}"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                /*
                 *  Simple image gallery. Uses default settings
                 */
    
                if($('.fancybox').length) $('.fancybox').fancybox();

            });
        </script>
    </body>
</html>