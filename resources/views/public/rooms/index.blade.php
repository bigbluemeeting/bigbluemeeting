@extends('admin.layouts.app')

@section('pagename', $pageName)

@section('css')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="{{asset('css/bootstrap-clockpicker.css')}}">
    <link rel="stylesheet" href="{{asset('css/rooms.css')}}">


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
                                        <th>Details</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($upComingMeetings as $list)
                                        <tr>
{{--                                            <td class="text-center"><input type="checkbox" name="rooms[]" value="{{$list->id}}"></td>--}}
                                            <td><a href="{{route('rooms.show',$list->url)}}">{{$list->name}}</a></td>
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
                                            <p class="text-danger m-0">We're sorry,you don't have any in-progress rooms or upcoming rooms.</p>
                                        </div>
                                        <div class="col-md-5">
                                            <p class="text-danger pt-1">To Create a new room,press the "Room" button</p>
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
        <h5>Past Rooms</h5>
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
                                            <p class="text-danger m-0">We're sorry,you don't have any past rooms.</p>
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





@endsection
@section('script')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
    <script src="https://momentjs.com/downloads/moment-with-locales.js"></script>
    <script src="{{asset('js/jquery-clockpicker.js')}}"></script>

    <script>
        url = '{{URL::to('rooms/:id/edit')}}';
        action =  "{{\Illuminate\Support\Facades\URL::to('rooms')}}/:id";
    </script>
    <script src="{{asset('js/rooms.js')}}"></script>

@stop



