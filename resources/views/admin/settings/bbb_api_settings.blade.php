@extends('admin.layouts.app')

@section('pagename', 'Settings')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-2">

                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if(Session::has($msg))
                        <div class="alert alert-{{ $msg }}">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            {{ session($msg) }}
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
        </div>


    @include('app_settings::_settings')
@stop
