<div class="container-fluid mt-4">
    <div class="row bg-light pt-5 pl-4">
        <div class="col-md-12" style="padding-left: 12%;">
            <h5 class="text-left"> You have been invited to join</h5>
            <h2 class="text-left mb-3 ">
                {{$room->name}}
            </h2>
            <hr class="mt-2 float-left w-25">
        </div>
    </div>
    <div class="row bg-light pt-3">
        <div class="col-md-5"  style="padding-left: 13%;">
            <span class="avatar mb-1" >{{strtoupper(substr($room->user->username,0,1))}}</span>
            <h5 class="font-weight-normal ml-2" style="display: inline-block">{{ucwords($room->user->username)}} (Owner) </h5>
        </div>
        <div class="col-md-6 mt-2">
            @if($room->all_join_moderator)
                <form action="{{route('attendeeJoinAsModerator')}}" method="post" >
                    @elseif($room->anyone_can_start)
                        <form action="{{route('attendeeStartRoom')}}" method="post" >
                            @else
                                <form action="{{route('meetingAttendeesJoin')}}" method="Post" id="frm">
                                    @endif
                                    @csrf
                                    <input type="hidden" value="{{encrypt($room->url)}}" name="room">
                                    <div class="input-group">
                                        <input   class="form-control join-form h-25" placeholder="Enter your name!" value="" autofocus="autofocus" type="text" name="name">
                                        <span class="input-group-append">
                                <button class="btn btn-primary btn-sm px-5  join-form" type="submit">
                                    @if($room->all_join_moderator)
                                        Start
                                    @elseif($room->anyone_can_start)
                                        Start
                                    @else
                                        Join
                                    @endif
                                </button>
                            </span>
                                    </div>
                                </form>

                                <div id="errorDiv">
                                    <span class="has-error text-danger float-left mb-3" id="error"></span>
                                </div>
                                <br><br><br><br>
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="row mt-2">
        <div class="col-md-6">
            <h5>Public Rooms Recordings</h5>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="table-responsive">
            <div class="col-md-12">
                <table class="table  table-hover" id="table" >
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Playback</th>
                        <th>State</th>
                        <th>Length</th>
                        <th>Users</th>
                        <th>Format</th>

                    </tr>
                    </thead>
                    <tbody>

                        @foreach($recordingList as  $list)
                            <tr>
                                <td>{{$list->name}}</td>
                                <td><a class="btn btn-sm btn-info " href="{{$list->playback->format->url}}">Watch</a></td>
                                <td>{{ucwords($list->state)}}</td>
                                <td>{{\App\Helpers\Helper::formatBytes($list->rawSize)}}</td>
                                <td class="text-center">{{$list->participants}}</td>
                                <td>{{ucwords($list->playback->format->type)}}</td>
                            </tr>

                        @endforeach

                    </tbody>
                    </table>

            </div>
        </div>
    </div>
</div>
