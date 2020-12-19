@extends('admin.layouts.app')

@section('pagename', $pageName)

@section('css')
    <link rel="stylesheet" href="{{ asset('css/bbb-custom.css') }}">
@stop

@section('content')
    <div class="title-block">
        <h3 class="title"> {{ $pageName }} </h3>
    </div>
    <div class="container-fluid">
        <div class="row" id="error">
            <div class="col-md-12">
                @if($errors->has('name'))
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        There was an error creating the room
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-12">

        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has($msg))
                <div class="alert alert-{{ $msg }}">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {{ session($msg) }}
                </div>
            @endif
        @endforeach
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card bg-white">
                <div class="card-body">
                    <div class="input-group">
                        <div class="input-group-prepend" >
                                <div class="title-block" id="createRoom" >
                                    <a><button type="button" class="btn btn-pill-right btn-primary"><i class="fa fa-plus-circle text-center text-white pr-1">&nbsp;</i> Create Room</button></a>
                                </div>
                        </div>

                    </div>
                    <section id="meeting-rooms">
                        <room-list delete-route="{{route('rooms.destroy',':id')}}" create-room-route="{{route('rooms.store')}}" update-room-route="{{route('rooms.update',':id')}}" single-room-route="{{route('rooms.edit',':id')}}" room-route="{{route('roomList')}}" room-details="{{route('showMeetingDetails',':id')}}" join-url="{{ route('JoinMeetings',':id') }}"></room-list>
                    </section>

                </div>
            </div>

        </div>
    </div>

    <br>

@endsection

@section('script')
    <script type="application/javascript" src="https://momentjs.com/downloads/moment-with-locales.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        url = '{{URL::to('rooms/:id/edit')}}';
        action =  "{{URL::to('rooms')}}/:deleteId";
    </script>
    <script src="{{asset('js/bbb-custom.js')}}"></script>
@stop
