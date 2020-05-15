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
    </style>

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
                                {{ $errors->first('name') }}

                            </div>
                        @endif
                        @if($errors->has('maximum_people'))
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                {{ $errors->first('maximum_people') }}
                            </div>

                        @endif
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
{{--                    data-target="#myModal"--}}
                    <button type="submit" name="commit" class="create-only btn btn-info btn-block" data-disable-with="Create Room" data-toggle="modal" id="createRoom" >
                        <i class="fa fa-plus"></i>
                        Create a Room
                    </button>

                </div>
            </div>
        </div>



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
                        {!! Form::open(['method' => 'POST', 'route' => ['rooms.store'], 'class'=>'form-horizontal']) !!}

                        <div class="row">
                            <div class="col-md-12">
                                <span class="input-icons">
                                <i class="fa fa-desktop icon mt-1 ml-2"></i>
                                </span>
                                <input id="create-room-name" class="form-control text-center input-field" value="" placeholder="Enter a Room name..." autocomplete="off" type="text" name="name">
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12 ">
                                <span class="input-icons">
                                <i class="fa fa-users icon mt-1 ml-2"></i>
                                </span>
                                <input id="create-room-name" class="form-control text-center input-field" value="" placeholder="Enter a Maximum People..." autocomplete="off" type="text" name="maximum_people">
                            </div>
                        </div>

                            <div class="row mt-3">

                                <div class="col-sm-6">
                                    <div class="input-group">

                                        <input type="text"  name="startDate" id="picker" placeholder="Enter Start Date" class="form-control text-center ">
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

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <input type="text"  name="endDate" id="picker2" placeholder="Enter End Date" class="form-control text-center">
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
                            <div class="mt-4 col-md-">
                                <input type="submit" value="Create Room" class="create-only btn btn-primary btn-block" data-disable-with="Create Room">
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

                            </tr>
                            </thead>
                            <tbody>


                            @foreach($roomList as $list)
                                <tr>

                                    <td><a href="{{route('rooms.show',$list->url)}}">{{$list->name}}</a></td>
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

        // init: function() {
        //     startTime.val(moment(new Date(), "h:mm:ss").format("hh:mm A"));
        //
        //     },
    });

    $('.clockpicker2').clockpicker({
        autoclose: true,
        twelvehour: true,
        placement: 'top',
        align: 'left',
        vibrate:true,
        // init: function() {
        //     endTime.val(moment(new Date(), "h:mm:ss").add(10,'minutes').format("hh:mm A"));
        //
        // },
    });


</script>

@stop

