<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title> @yield('pagename') | {{ env('APP_NAME') }} </title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <!-- Place favicon.ico in the root directory -->
    <link rel="stylesheet" href="{{ asset('css/vendor.css') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    @yield('css')
</head>
<body>
@include('public.layouts.sidebar')
@yield('content')
</body>
<script src="{{ asset('js/vendor.js') }}"></script>
<script>
$('.auth').hover(function () {
    $(this).removeClass('bg-white text-primary');
    $(this).addClass('text-white')
});
$('.auth').on('mouseout',function () {
    $(this).removeClass('text-white');
    $(this).addClass('bg-white text-primary')
});

</script>
@yield('script')
@yield('js')
</html>