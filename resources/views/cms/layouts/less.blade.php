<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Page title -->
    <title>{{ config('title', 'Login') }} | {{ config('app.name') }}</title>

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('static/fe-img/favicon.png')}}">

    <!-- styles -->
    <link rel="stylesheet" href="{{ asset(mix('static/css/app.css')) }}" />

</head>
<body class="blank">

    <!-- Main Wrapper -->
    <div class="login-container">
        <div class="row">
            <div class="col-md-12">
                <div class="text-center m-b-md">
                    <img src="{{asset('static/img/logo.png')}}" style="max-width: 100%;" />
                    <small style="display: block; font-size: 16px; margin: 0 0 15px; font-style: italic;">{{ config('app.title') }}</small>
                    <label>{{ config('title', 'Login') }}</label>
                </div>
                <div class="hpanel">
                    <div class="panel-body">

                        @yield('content')

                    </div>
                </div>
            </div>
        </div>
    
        <div class="row">
            <div class="col-md-12 text-center">
                <strong>{{ config('app.name') }}</strong> <br/> {{date("Y")!='2020' ? '2020-'.date("Y"):date("Y") }} All rights reserved
            </div>
        </div>
    </div>

    
    @include('const')
    <script src="{{ asset(mix('static/js/manifest.js')) }}"></script>
    <script src="{{ asset(mix('static/js/vendor.js')) }}"></script>
    <script src="{{ asset(mix('static/js/app.js')) }}"></script>
    
</body>

</html>