@extends('admin.layouts.app')

@section('pagename', $pageName)

@section('css')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('css/rooms.css?version=')}}{{time()}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />


@stop
@section('content')

    <div class="title-block">
        <h3 class="title"> {{ $pageName }} </h3>
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
    <div class="container-fluid">
        <h5>In-Progress and Upcoming Meetings</h5>
        <div class="row" id="error">
            <div class="col-md-12">
                @include('includes.form-errors')
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card bg-white">
                <div class="card-body">
                    <div class="input-group">
                        <div class="input-group-prepend" >
                            <div class="col-md-12">
                                <div class="title-block"  data-toggle="modal" id="createRoom">
                                    <a><button type="button" class="btn btn-pill-right btn-primary"><i class="fa fa-plus-circle text-center text-white pr-1">&nbsp;</i>Meeting</button></a>
                                </div>

{{--                                <span class="create-only btn btn-info btn-block input-group-text" data-toggle="modal" id="createRoom">--}}
{{--                                             <i class="fa fa-plus-circle text-center text-white pr-3">&nbsp; Rooms </i>--}}
{{--                                </span>--}}
                            </div>

                        </div>
                    </div>
                    @if(count($upComingMeetings)>0)
                        <section class="example">
                            <div class="row">
                                <div class="col-sm-6 col-sm-offset-5">
                                    {{$upComingMeetings->links()}}
                                </div>
                            </div>
                            <div class="table-responsive ">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Meeting Name</th>
                                        <th>Start</th>
                                        <th>End</th>
                                        <th>Recorded</th>
                                        <th>Details</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($upComingMeetings as $list)
                                        <tr>
{{--                                            <td class="text-center"><input type="checkbox" name="rooms[]" value="{{$list->id}}"></td>--}}
                                            <td><a href="{{route('meetings.show',$list->url)}}">{{$list->name}}</a></td>
                                            <td>{{\Carbon\Carbon::parse($list->start_date)->format('M d,yy g:i A')}}</td>
                                            <td>{{\Carbon\Carbon::parse($list->end_date)->format('M d,yy g:i A')}}</td>
                                            <td>{{$list->meeting_record ? 'Yes':'No'}}</td>
                                            <td>
                                                <a href="{{route('showDetails',$list->url)}}" class="btn btn-sm  btn-info">Show Details</a>
                                            </td>
                                            <td>
                                                <span data-task="{{$list->id}}"  class="btn btn-sm btn-info-outline btn-manage"><i class="fa fa-edit"></i> Edit</span>
                                                |
                                                <span href="javascript:;" data-toggle="modal"  data-item = {{$list->id}}
                                                   data-target="#DeleteModal" class="btn btn-sm btn-danger-outline btnDeleteConfirm"><i class="fa fa-trash"></i> Delete</span>

                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="row">
                                <div class="col-sm-6 col-sm-offset-5">
                                    {{$upComingMeetings->links()}}
                                </div>
                            </div>
                        </section>
                    @else
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="card">
                                    <div class="card-body" id="warning-dev">
                                        <div class="col-md-7">
                                            <p class="text-danger m-0">We're sorry,you don't have any in-progress meetings or upcoming meetings.</p>
                                        </div>
                                        <div class="col-md-5">
                                            <p class="text-danger pt-1">To Create a new meeting,press the "Meeting" button</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>



    {{--  Modal For Create Meeting  --}}
    <div id="modal">

        @include('public.rooms.addRoomModal')
        @include('public.rooms.editRoomModal')
        @include('public.rooms.deleteRoomModal')
    </div>




    {{--Table For Meetings List--}}
    <div class="container-fluid mt-3">
        <h5>Past Meetings</h5>
    </div>

    <div class="row">
        <div class="col-lg-12">


            <div class="card card-block sameheight-item">
                <section class="example">

                        <div class="row">

                            <div class="col-sm-6 col-sm-offset-5">
                                {{$upComingMeetings->links()}}
                            </div>
                        </div>
                    @if (count($pastMeetings) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Meeting Name</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    <th>Recorded</th>
                                    <th>Action</th>

                                </tr>
                                </thead>
                                <tbody>
                                @foreach($pastMeetings as $list)
                                    <tr>

                                        <td>{{$list->name}}</td>
                                        <td>{{\Carbon\Carbon::parse($list->start_date)->format('M d,yy g:i A')}}</td>
                                        <td>{{\Carbon\Carbon::parse($list->end_date)->format('M d,yy g:i A')}}</td>
                                        <td>{{$list->meeting_record ? 'Yes':'No'}}</td>
                                        <td>
                                             <span href="javascript:;" data-toggle="modal"  data-item = {{$list->id}}
                                                     data-target="#DeleteModal" class="btn btn-sm btn-danger-outline btnDeleteConfirm"><i class="fa fa-trash"></i> Delete</span>


                                        </td>
                                    </tr>
                                @endforeach




                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="card">
                                    <div class="card-body" id="warning-dev">
                                        <div class="col-md-7">
                                            <p class="text-danger m-0">We're sorry,you don't have any past meetings.</p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-5">
                                {{$upComingMeetings->links()}}
                            </div>
                        </div>
                </section>
            </div>


        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
    <script src="https://momentjs.com/downloads/moment-with-locales.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>

    <script type="text/javascript">

            $('#datetimepicker1').datetimepicker({
                format: 'LT',


            });
            $('#datetimepicker2').datetimepicker({
                format: 'LT',

            });
            $('#datetimepicker3').datetimepicker({
                format: 'LT',

            });
            $('#datetimepicker4').datetimepicker({
                format: 'LT',

            });

    </script>

@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>

    <script>
        url = '{{URL::to('meetings/:id/edit')}}';
        action =  "{{\Illuminate\Support\Facades\URL::to('meetings')}}/:id";
    </script>
    <script src="{{asset('js/rooms.js?version=')}}{{time()}} "></script>

@stop



