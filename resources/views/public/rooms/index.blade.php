@extends('admin.layouts.app')

@section('pagename', $pageName)

@section('css')

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
                            <div class="table-responsive ">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Room Name</th>
                                        <th>Start</th>
                                        <th>End</th>
                                        <th>Recorded</th>
                                        <th>Invitation</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($upComingMeetings as $list)
                                        <tr>
                                            <td><a href="{{route('rooms.show',$list->url)}}">{{$list->name}}</a></td>
                                            <td>{{\Carbon\Carbon::parse($list->start_date)->format('M d,yy g:i A')}}</td>
                                            <td>{{\Carbon\Carbon::parse($list->end_date)->format('M d,yy g:i A')}}</td>
                                            <td>{{$list->meeting_record ? 'Yes':'No'}}</td>
                                            <td>
                                                <a href="{{route('invite-participant',$list->url)}}" class="btn btn-sm  btn-info">Add Participant</a>
                                            </td>
                                            <td>
                                                <span data-task="{{$list->id}}"  class="btn btn-sm btn-info btn-manage" >Edit</span>
                                                |
                                                {!! Form::open(array(
                                                    'style' => 'display: inline-block;',
                                                    'method' => 'DELETE',
                                                    'onsubmit' => "return confirm('Are you sure do you want to delete?');",
                                                    )) !!}
                                                {!! Form::submit('Delete', array('class' => 'btn btn-sm btn-danger')) !!}
                                                {!! Form::close() !!}
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
    <div id="modal">
        <div id="saveModalData">
            @include('public.rooms.addRoomModal')
        </div>

        <div id="editModalData">

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
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Room Name</th>
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
                                            {!! Form::open(array(
                                                    'style' => 'display: inline-block;',
                                                    'method' => 'DELETE',
                                                    'onsubmit' => "return confirm('Are you sure do you want to delete?');",
                                                    )) !!}
                                            {!! Form::submit('Delete', array('class' => 'btn btn-sm btn-danger')) !!}
                                            {!! Form::close() !!}

                                        </td>
                                    </tr>
                                @endforeach
                                @else
                                    No Meetings Found.
                                @endif

                                </tbody>
                            </table>
                        </div>
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
    <script src="https://momentjs.com/downloads/moment-with-locales.js"></script>
    <script src="{{asset('js/jquery-clockpicker.js')}}"></script>

    <script>

        $('.picker').val(moment(new Date()).format("YYYY-MM-DD"));
        $('.picker2').val(moment(new Date()).format("YYYY-MM-DD"));
        var  startTime =$('#startTime');
        var  endTime =$('#endTime');


        $('#createRoom').on('click',function () {

            startTime.val(moment(new Date(), "h:mm:ss").format("hh:mm A"));
            endTime.val(moment(new Date(), "h:mm:ss").add(10,'minutes').format("hh:mm A"));


            $('#myModal').modal('show');
        });
        $('.btn-manage').on('click',function () {
            var id = $(this).data('task');

            url = '{{URL::to('rooms/')}}/'+id+'/edit';

            $.get(url,function (data) {
                $('#editModalData').empty().append(data);
                $('#editModal').modal('show');

            });
            dateTimePickers();
        });

        $('#advanceSettings').on('click',function () {
           $('#advancedOptions').slideToggle()
        });

        dateTimePickers();
       function dateTimePickers() {
           $('#modal').find('.picker').datetimepicker({
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

           $('#modal').find('.picker2').datetimepicker({
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
       }


    </script>

@stop

