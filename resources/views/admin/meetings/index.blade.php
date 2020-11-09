@extends('admin.layouts.app')
@section('pagename', $pageName)
@section('css')
    <style>
        .input-icons i {
            position: absolute;
        }

        .input-icons {
            width: 100%;
            margin-bottom: 10px;
        }
        .input-icon .cursor-pointer
        {
            cursor: pointer;
        }

        .icon {
            padding: 10px;
            min-width: 40px;
        }
        .delete-icon{
            margin-top: -45px;
        }
        input[type="checkbox"]
        {
            position: relative;
            width: 70px;
            height: 30px;
            -webkit-appearance: none;
            background: #c6c6c6;
            outline: none;
            border-radius: 30px;
            box-shadow: inset 0 0 5px rgba(0,0,0,2);
            transition: .5s;
        }
        input:checked[type="checkbox"]
        {
            background: #03a9f4;
        }
        input[type="checkbox"]:before
        {
            content: '';
            position: absolute;
            width: 30px;
            height: 30px;
            border-radius: 100px;
            top: 0;
            left: 0;
            background: #fff;
            transform: scale(1.1);
            box-shadow: 0 2px 5px rgba(0,0,0,2);
            transition: .5s;
        }
        input:checked[type="checkbox"]:before
        {
            left: 40px;
        }

    </style>
@stop
@section('content')

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
                            <div class="col-md-12">
                                <div class="title-block" id="createRoom" >
                                    <a><button type="button" class="btn btn-pill-right btn-primary"><i class="fa fa-plus-circle text-center text-white pr-1">&nbsp;</i> Rooms</button></a>
                                </div>
                            </div>

                        </div>

                    </div>
                    <section class="example">
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
    <script>
        $(document).ready(function () {

            $('#createRoom').on('click',function () {
              $('#myModal').modal('show')
            });

            $('.attendeeJoin').on('click',function () {
                let meeting = $(this).data('id');
                setInterval(function () {
                    $.ajax({
                        type:'POST',
                        url:'{{route("JoinAuthAttendee")}}',
                        datatype:'json',
                        data:{
                            meeting:meeting,
                            "_token":"{{csrf_token()}}"
                        },success:function (data) {

                            if (data.notStart)
                            {
                                $("#overlay").fadeIn(300);
                            }
                            if (data.url)
                            {
                                window.location = data.url;
                            }

                        },

                    });
                },2000);

            });

        });


    </script>
@stop
