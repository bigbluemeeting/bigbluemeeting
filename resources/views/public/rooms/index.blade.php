@extends('admin.layouts.app')

@section('pagename', $pageName)

@section('css')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('css/bootstrap-clockpicker.css')}}">

    <style>

        .input-icons i {
            position: absolute;
        }

        .input-icons {
            width: 100%;
            margin-bottom: 10px;
        }

        .icon {
            padding: 10px;
            min-width: 40px;
        }

        .input-field {
            width: 100%;
            padding: 10px;
            text-align: center;
        }
        #warning-dev{
            background: #fff8a0;
        }
        .modal-lg {
            max-width: 900px;
        }
        @media (min-width: 768px) {
            .modal-lg {
                width: 100%;
            }
        }
    </style>

@stop
@section('content')

    <div class="title-block">
        <h3 class="title"> {{ $pageName }} </h3>
    </div>

        <div class="container-fluid">
            <h5>In-Progress and Upcoming Rooms</h5>
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
                                         <span class="create-only btn btn-info btn-block input-group-text" data-toggle="modal" id="createRoom">
                                             <i class="fa fa-plus-circle text-center text-white pr-3">&nbsp; Rooms </i>
                                         </span>
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
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>Room Name</th>
                                            <th>Start</th>
                                            <th>End</th>
                                            <th>Recorded</th>
{{--                                            <th>Running</th>--}}
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($upComingMeetings as $list)
                                                <tr>
                                                    <td><a href="{{route('rooms.show',$list->url)}}">{{$list->name}}</a></td>
                                                    <td>{{\Carbon\Carbon::parse($list->start_date)->format('M d,yy g:i A')}}</td>
                                                    <td>{{\Carbon\Carbon::parse($list->end_date)->format('M d,yy g:i A')}}</td>
                                                    <td>{{$list->meeting_record ? 'Yes':'No'}}</td>
{{--                                                    <td>--}}
{{--                                                        <select name="" id="" class="form-control w-50 h-25 bg-primary">--}}
{{--                                                            @if($list->start)--}}
{{--                                                                <option value="">Yes</option>--}}
{{--                                                            @else--}}
{{--                                                                <option value="">No</option>--}}
{{--                                                            @endif--}}
{{--                                                        </select>--}}
{{--                                                        {{$list->start ?'Yes':'No'}}--}}
{{--                                                    </td>--}}
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        </table>
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
                                                    <p class="text-danger">We're sorry,you don have any in-progress rooms or upcoming rooms.</p>
                                                </div>
                                                <div class="col-md-5">
                                                    <p class="text-danger">To Create a new room,press the "Room" button</p>
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

    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog  modal-dialog-centered" style="width:1250px;">
            <!-- Modal content-->
            <div class="modal-content">

                <div class="modal-body">
                    <div class="card-body p-sm-6">
                        <div class="card-title">
                            <h3 class="text-center">Create New Room</h3>
                            <h3 class="update-only" style="display:none !important">Room Settings</h3>
                        </div>
                        {!! Form::open(['method' => 'POST', 'route' => ['rooms.store'], 'class'=>'form-horizontal']) !!}

                        <div class="row">
                            <div class="col-md-12">
                                <span class="input-icons">
                                <i class="fa fa-desktop icon mt-1 ml-2"></i>
                                </span>
                                <input id="create-room-name" class="form-control text-center" value="" placeholder="Enter a Room name..." autocomplete="off" type="text" name="name">
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12">
                                <span class="input-icons">
                                <i class="fa fa-users icon mt-1 ml-2"></i>
                                </span>
                                <input id="create-room-name" class="form-control text-center" value="" placeholder="Enter a Maximum People..." autocomplete="off" type="text" name="maximum_people">
                            </div>
                        </div>
                        <label for="start_date" class="mt-2" >Room Start on</label>
                           <div class="row">
                               <div class="col-sm-6">
                                    <div class="input-group">
                                        <input type="text"  name="start_date" id="picker" placeholder="Enter Start Date" class="form-control text-center">
                                        <div class="input-group-prepend">
                                            <span id="toggle" class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                               <p class="mt-2">at</p>
                                <div class="col-sm-5 clockpicker1">
                                    <div class="input-group">
                                        <input type="text" name="startTime" class="form-control" id="startTime">
                                        <div class="input-group-append">
                                            <span type="button" id="toggle3" class="input-group-text">
                                                <i class="fa fa-clock-o"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        <label for="end_date">Room End on</label>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <input type="text"  name="end_date" id="picker2" placeholder="Enter End Date" class="form-control text-center">
                                    <div class="input-group-prepend">
                                        <span type="button" id="toggle2" class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-2">at</p>
                                <div class="col-sm-5 clockpicker2">
                                    <div class="input-group">
                                    <input type="text" name="endTime" class="form-control" id="endTime" >
                                        <div class="input-group-append">
                                            <span type="button" id="toggle3" class="input-group-text">
                                                <i class="fa fa-clock-o"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <div class="row">
                            <div class="col-md-12">
                                <textarea class="form-control" name="meeting_description"  placeholder="A description of the invite to be send along with the e-mail invite" id="" cols="40" rows="2"></textarea>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12">

                                <textarea class="form-control" name="welcome_message"  placeholder="A welcome message shown in the chat room" id="" cols="40" rows="1.5"></textarea>
                            </div>
                        </div>


                        <div class="row mt-2">

                            <div class="col-md-6">
                                <label>Record This Room</label>
                                <select name="meeting_record"  class="form-control">
                                    <option value="{{encrypt(false)}}">No,don't record it.</option>
                                    <option value="{{encrypt(true)}}">Record it.</option>
                                </select>

                            </div>
                        </div>



                        <div class="row">
                            <div class="mt-3 ml-3">
                                <input type="submit" value="Schedule Room" class="create-only btn btn-info btn-block" data-disable-with="Create Room">
                                <input type="submit" name="commit" value="Update Room" class="update-only btn btn-primary btn-block" data-disable-with="Update Room" style="display:none !important">
                            </div>
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
    </div>

    {{--Table For Meetings List--}}
    <div class="container-fluid mt-3">
        <h5>Past Rooms</h5>
    </div>

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
                    @if (count($pastMeetings) > 0)
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-5">
                                {{$upComingMeetings->links()}}
                            </div>
                        </div>
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Room Name</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Recorded</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($pastMeetings as $list)
                                <tr>
                                    <td>{{$list->name}}</td>
                                    <td>{{\Carbon\Carbon::parse($list->start_date)->format('M d,yy g:i A')}}</td>
                                    <td>{{\Carbon\Carbon::parse($list->end_date)->format('M d,yy g:i A')}}</td>
                                    <td>{{$list->meeting_record ? 'Yes':'No'}}</td>
                                </tr>
                            @endforeach
                            @else
                                No Meetings Found.
                            @endif

                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-5">
                                {{$upComingMeetings->links()}}
                            </div>
                        </div>
                </section>
            </div>


        </div>
    </div>





@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
    <script src="https://momentjs.com/downloads/moment-with-locales.js"></script>
    <script src="{{asset('js/jquery-clockpicker.js')}}"></script>
<script>



    $('#picker').val(moment(new Date()).format("YYYY-MM-DD"));
    $('#picker2').val(moment(new Date()).format("YYYY-MM-DD"));
    var startTime =$('#startTime');
    var  endTime =$('#endTime');


    $('#createRoom').on('click',function () {

        startTime.val(moment(new Date(), "h:mm:ss").format("hh:mm A"));
        endTime.val(moment(new Date(), "h:mm:ss").add(10,'minutes').format("hh:mm A"));

        $('#myModal').modal('show');
    });


    $('#picker').datetimepicker({
        timepicker: false,
        datepicker: true,
        format: 'Y-m-d',
        // formatDate

        onShow :function () {
            this.setOptions({
                    maxDate : $('#picker2').val() ? $('#picker2').val():false
            })
        }
    });

    $('#picker2').datetimepicker({
        timepicker: false,
        datepicker: true,
        format: 'Y-m-d', // formatDate
        onShow :function () {
            this.setOptions({
                minDate : $('#picker').val() ? $('#picker').val():false
            })
        }
    });

    $('.clockpicker1').clockpicker({
        autoclose: true,
        twelvehour: true,
        placement: 'bottom',
        align: 'left',
        vibrate:true,

    });

    $('.clockpicker2').clockpicker({
        autoclose: true,
        twelvehour: true,
        placement: 'top',
        align: 'left',
        vibrate:true,

    });


</script>

@stop

