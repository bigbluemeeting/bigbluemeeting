@extends('admin.layouts.app')

@section('pagename', $pageName)
@section('css')
    <link rel="stylesheet" href="{{asset('css/ip.css')}}">

@stop
@section('content')

    <div class="container-fluid">

        <h5><i class="fa fa-users"></i>&nbsp;&nbsp;Meeting Information</h5>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card bg-white">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <tbody>
                            <tr>
                                <td>Meeting Name</td>
                                <td>{{$meeting->name}}</td>

                            </tr>
                            <tr>
                                <td>Start Time (Local)</td>
                                <td>{{\Carbon\Carbon::parse($meeting->start_date)->format('M d,yy g:i A')}}</td>

                            </tr>
                            <tr>
                                <td>End Time (Local)</td>
                                <td>{{\Carbon\Carbon::parse($meeting->end_date)->format('M d,yy g:i A')}}</td>
                            </tr>
                            <tr>
                                <td>No. of Participants</td>
                                <td>{{$meeting->maximum_people}}</td>

                            </tr>

                            <tr>
                                <td>Recording</td>
                                <td>{{$meeting->meeting_record ? 'Yes' : 'No'}}</td>
                            </tr>
                            <tr>
                                <td>Description</td>
                                <td>{{$meeting->meeting_description}}</td>
                            </tr>


                            </tbody>
                        </table>
                        <hr >
                        <div class="container-fluid">
                            <h5><i class="fa fa-user"></i>&nbsp;&nbsp;Meeting Participants</h5>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="col-md-11 mt-2">
                                    <span class="create-only btn btn-info btn-block input-group-text" data-toggle="modal" data-target="#myModal"id="createRoom">
                                        <i class="fa fa-plus-circle text-center text-white pr-3">&nbsp; Invite Participant <i class="fa fa-caret-down ml-1"></i></i>
                                    </span>
                                </div>
                            </div>
                            </div>
                        </div>


                    <div class="row">
{{--                        @if(count($attendees)>0)--}}
{{--                        <div class="col-sm-6 col-sm-offset-5 ml-3 mt-2">--}}
{{--                        {{$attendees->links()}}--}}
{{--                        </div>--}}
{{--                        @endif--}}
                        <div class="col-md-12">
                            @if(count($attendees)>0)
                                <div class="card bg-white m-0">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th>Email</th>
                                                <th>Created At</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($attendees as $attendee)
                                                <tr>
                                                    <td>{{$attendee->email}}</td>
                                                    <td>{{$attendee->created_at->diffForHumans()}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @else
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="card">
                                            <div class="card-body" style="background: #fff8a0;">
                                                <div class="col-md-7" >
                                                    <p class="text-danger m-0">There are currently no participants invited to your meeting.</p>
                                                    <p class="text-danger pt-1">To participants to this meeting,click the blue button on the top left.</p>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>

                        <div class="container-fluid {{count($attendees) > 0 ? '' :'mt-3' }}">
                            <hr class="m-0">
                        </div>
                            <div class="container mt-3">

                                <h5><i class="fa fa-folder-open"></i>&nbsp;&nbsp;Files</h5>
                            </div>

                            <div class="col-md-12">
{{--                                <div class="card bg-light">--}}
{{--                                    <div class="card-body">--}}
{{--                                        <div class="card">--}}


{{--                                        </div>--}}

{{--                                    </div>--}}
{{--                                </div>--}}
                                <table class="table table-hover table-bordered">
                                    <thead>
                                    <tr>
                                        <th class="bg-light" colspan="4">

                                            <form action="">
                                                <div class="row mt-3">
                                                    <div class="col-md-4 ml-2">
                                                        <div class="form-group">
{{--                                                            {!!Form::label('Title', 'Title')!!}--}}
                                                            {!!Form::file('title',null, ['class'=>'form-control'])!!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-7 ml-5">

                                                            {!! Form::submit('Upload File',['class'=>'btn btn-info']) !!}

                                                            <a class="btn btn-info ml-4" href="">Manage File</a>
{{--                                                            {!! Form::submit('Create Post',['class'=>'btn btn-primary']) !!}--}}

                                                    </div>
                                                </div>



                                            </form>
                                        </th>

                                    </tr>
                                    <tr>
                                        <th>File</th>
                                        <th>Date</th>
                                        <th>Mime</th>
                                        <th>Size</th>
                                    </tr>


                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>John</td>
                                        <td>Doe</td>
                                        <td>john@example.com</td>

                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-6 col-sm-offset-5 ml-3">
                                {{$attendees->links()}}
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



        {{-- Add Participant Modal   --}}
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered">

            <!-- Modal content-->
            <div class="modal-content">

                <div class="modal-body">
                    <div class="card-body p-sm-6">
                        <div class="card-title">
                            <h3 class="text-center">Invite Participants</h3>
                            <h3 class="update-only" style="display:none !important">Room Settings</h3>
                        </div>
{{--                        {!! Form::open(['method' => 'POST', 'route' => ['meetings.store'], 'class'=>'form-horizontal','id'=>'frm']) !!}--}}
                        <div class="alert alert-danger errorClass" style="display: none">

                        </div>


                        <div class="input-icon mb-2">
{{--                            <span class="input-icons">--}}
{{--                                <i class="fa fa-envelope icon ml-2"></i>--}}
{{--                            </span>--}}
{{--                            class="form-control text-center" value="" placeholder="Enter Participants Email..." autocomplete="off" type="text" name="name"--}}
                            <input id="testInput" >
{{--                            <input  type="text" id="testInput" value=""/>--}}
                        </div>


                        <div class="row">
                            <div class="mt-3 ml-3">
                                <input type="submit" value="Add Participants" class="create-only btn btn-primary btn-block" id="addPar" >
                                <input type="submit" name="commit" value="Update Room" class="update-only btn btn-primary btn-block" data-disable-with="Update Room" style="display:none !important">
                            </div>
                        </div>

{{--                        {!! Form::close() !!}--}}
                    </div>
                </div>
                <input type="hidden" id="room" value="{{$meeting->url}}">
                <div class="modal-footer bg-light">
                    <p class="text-primary"><strong> Info ! </strong> Participants need to singup if he's not member of this site. Invitational mail will be sent to his email </p>

                </div>
            </div>

        </div>
    </div>





@stop

@section('script')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>

        var slug = $('#room').val();
        var postUrl = '{{route("roomAttendees")}}';
        var  url =  "{{ route('invite-participant',":slug")}}";
        var csrf = '{{csrf_token()}}'

    </script>
    <script src="{{asset('js/ip.js')}}"></script>
@stop