<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="{{ config('app.name') }} - {{ config('description', config('current_menu.description')) ?? 'There is no description on this page' }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Page title -->
    <title>{{ config('title', config('current_menu.name', 'Dashboard')) }} - {{ config('app.name') }}</title>

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <!--<link rel="shortcut icon" type="image/ico" href="favicon.ico" />-->

    <!-- styles -->
    <link rel="stylesheet" href="{{ asset(mix('static/css/app.css')) }}" />

</head>
<body class="fixed-navbar sidebar-scroll" data-page="{{Helper::page()}}">

    <!-- Header -->
    <div id="header">
        <div class="color-line">
        </div>
        <div id="logo" class="light-version">
            <span>
                <a href="{{ url('') }}" target="_blank">
                    <h5>Project Test</h5>
                </a>
            </span>
        </div>
        <nav role="navigation">
            <div class="header-link hide-menu"><i class="fa fa-bars"></i></div>
            <div class="small-logo" style="padding-top: 10px;">
                <span class="text-primary" style="font-size: 14px; font-weight: bold; line-height: 14px; text-align: left;">
                    {{config('app.name')}}
                </span>
                <small style="display: block; font-size: 9px; font-weight: normal; text-align: left;">
                    {{config('app.title')}}
                </small>
            </div>
            <div class="navbar-form-custom" style="padding-top: 10px;width: 500px;">
                <span class="text-primary" style="font-size: 18px; font-weight: bold; line-height: 18px;">
                    {{config('app.name')}}
                </span>
                <small style="display: block">
                    {{config('app.title')}}
                </small>
            </div>
            
            
            <div class="navbar-right">
                <ul class="nav navbar-nav no-borders">
                    <li class="dropdown">
                        <a class="label-menu-corner" href="{{ Helper::CMS('notif') }}">
                            <i class="pe-7s-bell"></i>
                            <span class="label label-danger notif-count">0</span>
                        </a>
                    </li>
                    <li class="dropdown">
                        <a href="{{ Helper::CMS('logout') }}">
                            <i class="pe-7s-upload pe-rotate-90"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

    <!-- Navigation -->
    <aside id="menu">
        <div id="navigation">
            <div class="profile-picture">
                <a href="{{ Helper::CMS('profile') }}">
                    <img src="{{ Helper::ImgSrc(Session::get('user.photo'), config('app.thumb_size')) }}" class="img-circle m-b" alt="logo" style="max-height: 140px;object-fit: contain;background:#000;">
                </a>
    
                <div class="stats-label text-color">
                    <span class="font-extra-bold font-uppercase">{{ Session::get('user.name') }}</span>
    
                    <div class="dropdown">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                            <small class="text-muted">{{ Session::get('user.relrole.name') }} <b class="caret"></b></small>
                        </a>
                        <ul class="dropdown-menu animated flipInX m-t-xs">
                            <li><a href="{{ Helper::CMS('profile') }}">Profile</a></li>
                            <li><a href="{{ Helper::CMS('logout') }}">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        
            @if( Session::has('menu') )
            
            @php
            
            function buildMenu($menu)
            {
                $list = '';

                if( $menu )
                {
                    foreach( $menu as $m )
                    {
                        $r = Session::get('menu.data.'.$m);

                        $child = '';

                        if( $p = Session::get('menu.parent_id.'.$m) )
                        {
                            $child = '<ul class="nav nav-second-level sub-level">'.buildMenu($p).'</ul>';
                        }

                        $list.= '<li class="'.(Helper::page()==Helper::val($r, 'slug') ? 'active' : '').'">
                                    <a href="'.Helper::CMS(Helper::val($r, 'slug')).'"> 
                                        <span class="nav-label"><i class="'.Helper::val($r, 'icon').'"></i> '. Helper::val($r, 'name') .'</span> 
                                        '.($child?'<span class="fa arrow"></span>':'').'
                                    </a>
                                    '.$child.'
                                </li>';
                    }
                }

                return $list;
            }
            @endphp

            <ul class="nav" id="side-menu">
                {!! buildMenu(Session::get('menu.parent_id.0')) !!}
            </ul>
            
            @endif
                
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div id="wrapper">
        
        @if( Helper::page()!='dashboard' && config('breadcrumb') )
        <div class="small-header">
            <div class="hpanel">
                <div class="panel-body">
                    <div class="pull-right">
                        @yield('button_top_right')
                        <button class="btn btn-default" type="button" data-toggle="modal" data-target="#modalHelp"><i class="fa fa-question" data-toggle="tooltip" title="{{__('Bantuan')}}" data-placement="bottom"></i></button>
                    </div>
                    <h2 class="font-light m-b-xs">
                        {{ config('title', config('current_menu.name', 'Dashboard')) ?? 'Untitled' }}
                        <small>{{ config('description', config('current_menu.description')) ?? '' }}</small>
                    </h2>
                    <div id="hbreadcrumb">
                        <ol class="hbreadcrumb breadcrumb">
                            @foreach( config('breadcrumb') as $k=>$b )
                                <li><i class="{{$b['icon']}}"></i> <a href="{{ $b['url'] }}">{{ $b['title'] }}</a></li>
                            @endforeach
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <div class="content">
            @include('components.alert')
            @yield('content')
        </div>

    <!-- Footer-->
    <footer class="footer">
        <span class="pull-right">
            All Rights Reserved
        </span>
        &copy; {{ date("Y") }} {{config('app.name')}}
    </footer>
        
    <x-form.imagebank/>

    <x-index.help/>
    
    @include('const')
    @stack('script')
    <script src="{{ asset(mix('static/js/manifest.js')) }}"></script>
    <script src="{{ asset(mix('static/js/vendor.js')) }}"></script>
    <script src="{{ asset(mix('static/js/app.js')) }}"></script>
    
</body>

</html>