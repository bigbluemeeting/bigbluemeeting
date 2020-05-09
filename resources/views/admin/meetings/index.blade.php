@extends('admin.layouts.app')

@section('pagename', $pageName)

@section('content')

    <div class="title-block">
        <h3 class="title"> {{ $pageName }} </h3>
    </div>

    @if(Gate::check('moderate') || Gate::check('users_manage') || Gate::check('master_manage'))
    <div class="container-fluid">
        <div class="row" id="error">
            <div class="col-md-12">
                @if($errors->has('name'))
                    <p class="alert alert-danger text-center">
                        There was an error creating the room
                    </p>

                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <button type="submit" name="commit" class="create-only btn btn-info btn-block" data-disable-with="Create Room" data-toggle="modal" data-target="#myModal">
                    <i class="fa fa-plus"></i>
                    Create a Room
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
                            <h3 class="text-center">Create New Room</h3>
                            <h3 class="update-only" style="display:none !important">Room Settings</h3>
                        </div>
                        {!! Form::open(['method' => 'POST', 'route' => ['admin::meetings.store'], 'class'=>'form-horizontal']) !!}

                        <div class="input-icon mb-2">
                            <span class="input-icon-addon">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </span>
                            <input id="create-room-name" class="form-control text-center" value="" placeholder="Enter a room name..." autocomplete="off" type="text" name=" name">

                        </div>

                        <div class="input-icon mb-2">
                            <span onclick="generateAccessCode()" class="input-icon-addon allow-icon-click cursor-pointer">
                                <i class="fas fa-dice"></i>
                            </span>
                            <label id="create-room-access-code" class="form-control" for="room_access_code">Generate an optional room access code</label>
                            <input type="hidden" value="" name="access_code" id="room_access_code">
                            <span onclick="ResetAccessCode()" class="input-icon-addon allow-icon-click cursor-pointer">
                                <i class="fa fa-trash-alt"></i>
                            </span>
                        </div>
                        <label class="custom-switch pl-0 mt-3 mb-3 w-100 text-left d-inline-block ">
                            <span class="custom-switch-description">Mute users when they join</span>
{{--                            <input name="mute_on_join" type="hidden" value="0">--}}
                            <input class="custom-switch-input" data-default="false" type="checkbox" value="1" name="mute_on_join" id="room_mute_on_join">
{{--                            <span class="custom-switch-indicator float-right cursor-pointer"></span>--}}

                        </label>

                            <label class="custom-switch pl-0 mt-3 mb-3 w-100 text-left d-inline-block ">
                                <span class="custom-switch-description">Require moderator approval before joining</span>
{{--                                <input name="require_moderator_approval" type="hidden" value="0">--}}
                                <input class="custom-switch-input" data-default="false" type="checkbox" value="1" name="require_moderator_approval" id="room_require_moderator_approval">
{{--                                <span class="custom-switch-indicator float-right cursor-pointer"></span>--}}
                            </label>

                        <label class="custom-switch pl-0 mt-3 mb-3 w-100 text-left d-inline-block ">
                            <span class="custom-switch-description">Allow any user to start this meeting</span>
{{--                            <input name="anyone_can_start" type="hidden" value="0">--}}
                            <input class="custom-switch-input" data-default="false" type="checkbox" value="1" name="anyone_can_start" id="room_anyone_can_start">
{{--                            <span class="custom-switch-indicator float-right cursor-pointer"></span>--}}
                        </label>

                        <label class="custom-switch pl-0 mt-3 mb-3 w-100 text-left d-inline-block ">
                            <span class="custom-switch-description">All users join as moderators</span>
{{--                            <input name="all_join_moderator" type="hidden" value="0">--}}
                            <input class="custom-switch-input" data-default="false" type="checkbox" value="1" name="all_join_moderator" id="room_all_join_moderator">
{{--                            <span class="custom-switch-indicator float-right cursor-pointer"></span>--}}
                        </label>

                        <label id="auto-join-label" class="create-only custom-switch pl-0 mt-3 mb-3 w-100 text-left d-inline-block">
                            <span class="custom-switch-description">Automatically join me into the room</span>
{{--                            <input name="auto_join" type="hidden" value="0" style="display: inline-block;">--}}
                            <input class="custom-switch-input" type="checkbox" value="1" name="auto_join" id="room_auto_join">
{{--                            <span class="custom-switch-indicator float-right cursor-pointer"></span>--}}
                        </label>

                        <div class="mt-4">
                            <input type="submit" value="Create Room" class="create-only btn btn-primary btn-block" data-disable-with="Create Room">
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
                                    {{--                        <th>#</th>--}}
                                    <th>Room Name</th>
                                    <th>Create Date</th>
                                    <th>Created By</th>


                                </tr>
                                </thead>
                                <tbody>


                                @foreach($roomList as $list)
                                    <tr>

                                        <td><a href="{{route('admin::meetingAttendees',$list->id)}}">{{$list->name}}</a></td>
                                        <td>{{$list->created_at->diffForHumans()}}</td>
                                        <td>{{$list->user->name}}</td>


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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
$(document).ready(function () {
    $("#error").show().delay(5000).fadeOut();

});
</script>
    @stop

