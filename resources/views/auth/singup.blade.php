<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title> SingUp | {{ env('APP_NAME') }} </title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <!-- Place favicon.ico in the root directory -->

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
    <link rel="stylesheet" href="{{ asset('css/vendor.css') }}">
</head>
<body>
<div class="auth">
    <div class="auth-container">
        <div class="card">
            <header class="auth-header">
                <h1 class="auth-title">
                    <div class="logo">
                        <span class="l l1"></span>
                        <span class="l l2"></span>
                        <span class="l l3"></span>
                        <span class="l l4"></span>
                        <span class="l l5"></span>
                    </div> {{ env('APP_NAME') }}
                </h1>
            </header>
            <div class="auth-content">
                <p class="text-center">SIGNUP TO CONTINUE</p>
                <form id="login-form" action="{{ route('register') }}" method="POST">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if(Session::has($msg))
                        <div class="alert alert-{{ $msg }}">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            {{ session($msg) }}
                        </div>
                    @endif

                    @endforeach

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group {{ $errors->has('name') ? 'has-error':'' }}">
                        <label for="name">Name </label>
                        <input type="text" class="form-control underlined" name="name" id="name" placeholder="Your Name"value="{{old('name')}}">
                        @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>


                    <div class="form-group {{ $errors->has('username1') ? 'has-error':'' }}">
                        <label for="username1 ">Username</label>
                        <input type="text" class="form-control underlined" name="username1"  placeholder="Your Username" value="{{old('username1')}}">
                        @if ($errors->has('username1'))
                            <span class="text-danger">{{ $errors->first('username1') }}</span>
                        @endif
                    </div>


                    @if(!empty($meeting))
                    <input type="hidden"  name="meeting_id" value="{{$meeting}}">
                    @endif

                        @if(!empty($userEmail))
                            <div class="form-group {{ $errors->has('email') ? 'has-error':'' }}" style="display: none">
                                <input type="hidden" class="form-control underlined" name="email" readonly="true" placeholder="Your email address" value="{{$userEmail}}">

                            </div>
                            @else
                            <div class="form-group {{ $errors->has('email') ? 'has-error':'' }}">
                                <label for="email">E-Mail Address</label>

                                <input type="text" class="form-control underlined" name="email"  placeholder="Your email address" value="{{old('email')}}">

                                @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        @endif





                    <div class="form-group {{ $errors->has('password1') ? 'has-error':'' }}">
                        <label for="password">Password</label>
                        <input type="password" class="form-control underlined" name="password1"  placeholder="Your password">
                        @if ($errors->has('password1'))
                            <span class="text-danger">{{ $errors->first('password1') }}</span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="remember">
                            <input class="checkbox" id="remember" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}>
                            <span>Remember me</span>
                        </label>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-block btn-primary">Sign Up</button>
                    </div>

                        <div class="text-center">If you have an account ?
                            <a class="large text-success"  style="text-decoration: none" href="{{route('login')}}">Sign In</a>
                        </div>

                </form>
            </div>
        </div>

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
<script src="{{ asset('js/vendor.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>