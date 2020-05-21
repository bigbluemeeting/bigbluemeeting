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
        #delete-icon{
            margin-top: -45px;
        }

    </style>
@stop
@section('content')

    <div id="overlay">


        <div class="cv-spinner">

            <span class="newspinner">
            </span>
        </div>
    </div>

    <div class="title-block">
        <h3 class="title"> {{ $pageName }} </h3>
    </div>

    @if(Gate::check('moderate') || Gate::check('users_manage') || Gate::check('master_manage'))
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

        <div class="row">
            <div class="col-md-3">
                <button type="submit" name="commit" class="create-only btn btn-info btn-block" data-disable-with="Create Room" data-toggle="modal" data-target="#myModal">
                    <i class="fa fa-plus"></i>
                    Create Room
                </button>

            </div>
        </div>
    </div>
    @endif


    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered">

            <!-- Modal content-->
            <div class="modal-content">

                <div class="modal-body">
                    <div class="card-body p-sm-6">
                        <div class="card-title">
                            <h3 class="text-center">Create New Meeting</h3>
                            <h3 class="update-only" style="display:none !important">Room Settings</h3>
                        </div>
                        {!! Form::open(['method' => 'POST', 'route' => ['meetings.store'], 'class'=>'form-horizontal']) !!}

                        <div class="input-icon mb-2">
                            <span class="input-icons">
                                <i class="fa fa-desktop icon ml-2"></i>
                            </span>
                            <input id="create-room-name" class="form-control text-center" value="" placeholder="Enter a Meeting name..." autocomplete="off" type="text" name=" name">

                        </div>

                        <div class="input-icon mb-2">
                             <span class="input-icons cursor-pointer">
                                <i class="fa fa-lock icon ml-2 " id="generate_access_code"></i>
                            </span>
                            <label id="create-room-access-code" class="form-control text-sm-center" for="room_access_code">Generate an optional room access code</label>
                            <input type="hidden" value="" name="access_code" id="room_access_code">
                            <span  class="cursor-pointer" >
                                <i class="fa fa-trash-o float-right icon" id="delete-icon"></i>
                            </span>
                        </div>
                        <label class="custom-switch pl-0 mt-3 mb-3 w-100 text-left d-inline-block ">
                            <span class="custom-switch-description">Mute users when they join</span>
                            <input class="custom-switch-input" data-default="false" type="checkbox" value="1" name="mute_on_join" id="room_mute_on_join">

                        </label>

                            <label class="custom-switch pl-0 mt-3 mb-3 w-100 text-left d-inline-block ">
                                <span class="custom-switch-description">Require moderator approval before joining</span>
                                <input class="custom-switch-input" data-default="false" type="checkbox" value="1" name="require_moderator_approval" id="room_require_moderator_approval">

                            </label>

                        <label class="custom-switch pl-0 mt-3 mb-3 w-100 text-left d-inline-block ">
                            <span class="custom-switch-description">Allow any user to start this meeting</span>
                            <input class="custom-switch-input" data-default="false" type="checkbox" value="1" name="anyone_can_start" id="room_anyone_can_start">
                        </label>

                        <label class="custom-switch pl-0 mt-3 mb-3 w-100 text-left d-inline-block ">
                            <span class="custom-switch-description">All users join as moderators</span>
                            <input class="custom-switch-input" data-default="false" type="checkbox" value="1" name="all_join_moderator" id="room_all_join_moderator">
                        </label>

                        <label id="auto-join-label" class="create-only custom-switch pl-0 mt-3 mb-3 w-100 text-left d-inline-block">
                            <span class="custom-switch-description">Automatically join me into the room</span>
                            <input class="custom-switch-input" type="checkbox" value="1" name="auto_join" id="room_auto_join">

                        </label>

                        <div class="mt-4">
                            <input type="submit" value="Create Meeting" class="create-only btn btn-primary btn-block" data-disable-with="Create Room">
                            <input type="submit" name="commit" value="Update Room" class="update-only btn btn-primary btn-block" data-disable-with="Update Room" style="display:none !important">
                        </div>
                        {!! Form::close() !!}
                </div>
            </div>
                <div class="modal-footer">
                    <h6 style="margin-right: 60px;">You will be free to delete this room at any time.</h6>
                    <p class="update-only" style="display:none !important">Adjustment to your room can be done at anytime.</p>
                </div>
            </div>

        </div>
    </div><br>


    {{--Table For Meetings List--}}

        <div class="row">
            <div class="col-lg-12">


                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if(Session::has($msg))
                        <div class="alert alert-{{ $msg }}">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            {{ session($msg) }}
                        </div>
                    @endif
                @endforeach

                <div class="card card-block sameheight-item">


                    <section class="example">
                        @if (count($roomList) > 0)
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>

                                    <th>Room Name</th>
                                    <th>Create Date</th>
                                    <th>Created By</th>
                                    <th>Invite Participants</th>
                                    <th>Action</th>


                                </tr>
                                </thead>
                                <tbody>


                                @foreach($roomList as $list)
                                    <tr>

                                        <td>{{$list->name}}</td>
                                        <td>{{$list->created_at->diffForHumans()}}</td>
                                        <td>{{$list->user->name}}</td>
                                        <td contenteditable="true">{{url()->current().'/'.$list->url}}</td>
                                        <td >
                                            @if(Gate::check('moderate') || Gate::check('users_manage') || Gate::check('master_manage'))
                                            <a href="{{ route('JoinMeetings',[$list->url]) }}" class="btn btn-lg  btn-info from-control">Start</a>
                                            @else
{{--                                                {{ route('admin::JoinAttendee',[$list->url]) }}--}}
                                                <a href='javascript:void(0)' data-id ="{{$list->url}}" class="btn btn-lg btn-info attendeeJoin from-control" id="">Join</a>
                                            @endif
                                        </td>



                                    </tr>
                                @endforeach
                                @else
                                    No Meetings Found.
                                @endif

                                </tbody>
                            </table>

                    </section>
            </div>
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-5">
                            {{$roomList->links()}}
                        </div>
                    </div>

        </div>
    </div>


@endsection


@section('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>
        $(document).ready(function () {

            $('#generate_access_code').on('click',function () {

                var arr = [];
                while(arr.length < 6){
                    var r = Math.floor(Math.random() * 9) + 1;
                    if(arr.indexOf(r) === -1) arr.push(r);
                }
                $('#room_access_code').val(arr.join(''));
                $('#create-room-access-code').text('Access Code: '+arr.join(''))

            });
            $('#delete-icon').on('click',function () {


                $('#room_access_code').val('');
                $('#create-room-access-code').text('Generate an optional room access code')

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
                                // console.log(data.notStart)
                                $("#overlay").fadeIn(300);
                            }
                            if (data.url)
                            {
                                // console.log(data.url)
                                window.location =data.url;
                            }

                        },

                    });
                },2000);

            });

        });


    </script>
@stop
