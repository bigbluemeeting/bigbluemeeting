<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title> @yield('pagename') | {{config('global.app_name') }} </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <!-- Place favicon.ico in the root directory -->
    <link rel="stylesheet" href="{{ asset('css/vendor.css') }}">

@yield('css')

    <!-- Theme initialization -->
    <script>
    var themeSettings = (localStorage.getItem('themeSettings')) ? JSON.parse(localStorage.getItem('themeSettings')) :
    {};
    var themeName = themeSettings.themeName || '';
    if (themeName)
    {
    document.write('<link rel="stylesheet" id="theme-style" href="{{ asset('') }}css/app-' + themeName + '.css">');
    }
    else
    {
    document.write('<link rel="stylesheet" id="theme-style" href="{{ asset('css/app.css') }}">');
    }
    </script>
</head>
<body>
<div class="main-wrapper">
    <div class="app" id="app">
        <header class="header">
            <div class="header-block header-block-collapse d-lg-none d-xl-none">
                <button class="collapse-btn" id="sidebar-collapse-btn">
                    <i class="fa fa-bars "></i>
                </button>
            </div>
            <div class="header-block header-block-nav">
                <ul class="nav-profile">
                    <li class="profile dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            <div class="img" style="background-image: url('https://avatars3.githubusercontent.com/u/9919?s=40&v=4')"> </div>
                            <span class="name"> {{ Auth::user()->name }} </span>
                        </a>
                        <div class="dropdown-menu profile-dropdown-menu" aria-labelledby="dropdownMenu1">
                            <a class="dropdown-item" href="#">
                                <i class="fa fa-user icon"></i> Profile
                            </a>



                            @if( Gate::check('users_manage') || Gate::check('master_manage'))

                            <a class="dropdown-item" href="{{ \Illuminate\Support\Facades\URL::to('settings') }}">
                                <i class="fa fa-gear icon"></i>  Settings

                            </a>
                            @endif
                            <div class="dropdown-divider"></div>
                            <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                                <i class="fa fa-power-off icon"></i> Logout </a>
                        </div>
                    </li>

                </ul>



            </div>
        </header>

        @include('admin.layouts.sidebar')

        <article class="content">
            @yield('content')
        </article>


        <footer class="footer">
            <div class="footer-block buttons">
                &copy; 2013-2020 {{config('global.app_name') }}
            </div>
            <div class="footer-block author">
                <ul>
                    <li > Created by
                        <a href="https://etopian.com" target="_blank">Etopian Inc.</a>
                    </li>
                </ul>
            </div>
        </footer>
    </div>
</div>
<!-- Reference block for JS -->
<div class="ref" id="ref">
    <div class="color-primary"></div>
    <div class="chart">
        <div class="color-primary"></div>
        <div class="color-secondary"></div>
    </div>
</div>


@yield('script')
<script src="{{ asset('js/vendor.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
@yield('js')
</body>
</html>
