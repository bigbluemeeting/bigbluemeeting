@extends('public.layouts.app')
@section('pagename', $pageName)
@section('css')
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/front.css?v=time()')}}">
@stop
@section('content')
    <div id="data">
        @include('includes.meetingAccessCodeForm')
    </div>


@endsection

@section('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script type="text/javascript" src={{asset('js/jquery.dataTables.min.js')}}></script>
    <!-- Data Bootstarp -->
    <script type="text/javascript" src={{asset('js/dataTables.bootstrap4.min.js')}}></script>
    <script>
        var code='{{route("accessCodeResult")}}';
        var join='{{route("meetingAttendeesJoin")}}';
    </script>
    <script src="{{asset('js/front/meetings/bbb-front.js')}}"></script>
@stop

