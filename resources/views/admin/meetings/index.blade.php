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
                                         <span class="create-only btn btn-info btn-block input-group-text" data-toggle="modal" id="createRoom" data-target="#myModal">
                                             <i class="fa fa-plus-circle text-center text-white pr-3">&nbsp; Meetings </i>
                                         </span>
                            </div>

                        </div>

                    </div>
                    @if(count($roomList)>0)
                        <section class="example">


                            <div class="row">
                                <div class="col-sm-6 col-sm-offset-5">
                                    {{$roomList->links()}}
                                </div>
                            </div>
                            <div class="table-responsive ">
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
                                                <a href="{{ route('JoinMeetings',[$list->url]) }}" class="btn btn-sm  btn-primary-outline ">Start</a>
                                                |
                                                <span data-task="{{$list->id}}"  class="btn btn-sm btn-info-outline btn-manage">
                                                    <i class="fa fa-edit"></i> Edit
                                                </span>
                                                |
                                                <span href="javascript:;" data-toggle="modal"  data-item = {{$list->id}} data-target="#DeleteModal" class="btn btn-sm btn-danger-outline btnDeleteConfirm">
                                                    <i class="fa fa-trash"></i> Delete
                                                </span>


                                            </td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="row">
                                <div class="col-sm-6 col-sm-offset-5">
                                    {{$roomList->links()}}
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


    <div id="dataModal">

        @include('admin.meetings.addMeetingModal')
        <div id="editModal" class="modal fade" role="dialog">
        </div>
        @include('public.rooms.deleteRoomModal')
    </div>


    <br>




@endsection


@section('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>
        url = '{{URL::to('meetings/:id/edit')}}';
        action =  "{{URL::to('meetings')}}/:id";
    </script>
    <script>
        $(document).ready(function () {

            $('#createRoom').on('click',function () {
                $('#dataModal').find('.room_access_code').val('');
                $('#dataModal').find('.create-room-access-code').text('Generate an optional room access code')

            });
            $('#dataModal').on('click','.generate_access_code',function () {

                var arr = [];
                while(arr.length < 6){
                    var r = Math.floor(Math.random() * 9) + 1;
                    if(arr.indexOf(r) === -1) arr.push(r);
                }
                $('#dataModal').find('.room_access_code').val(arr.join(''));
                $('#dataModal').find('.create-room-access-code').text('Access Code: '+arr.join(''))

            });
            $('#dataModal').on('click','.delete-icon',function () {


                $('#dataModal').find('.room_access_code').val('');
                $('#dataModal').find('.create-room-access-code').text('Generate an optional room access code')

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
            $('.btn-manage').on('click', function () {

                var id = $(this).data('task');
                url = url.replace(':id', id);



                $.get(url, function (data) {
                    $('#editModal').empty().append(data);
                   $('#editModal').modal('show');
                });
                url = url.replace(id,':id');
            });

            $('.btnDeleteConfirm').on('click',function () {


                id = $(this).data('item');
                deleteData(id)
            });
            function deleteData(id)
            {
                url = action.replace(':id', id);
                $("#deleteForm").prop('action', url);
            }
            $('.btnDelete').on('click',function () {
                $("#deleteForm").submit();
            });
        });


    </script>
@stop
