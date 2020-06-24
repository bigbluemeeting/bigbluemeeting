@extends('admin.layouts.app')
@section('pagename', $pageName)

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

                <div class="alert alert-danger errorClass" style="display: none">

                </div>


        <div class="card card-block sameheight-item">

            <div class="row">
                <div class="col-sm-6 col-sm-offset-5">
                    {{$roomList->links()}}
                </div>
            </div>
            <section class="example">

                @if (count($roomList) > 0)
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>Room Name</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>


                        @foreach($roomList as $list)
                            <tr>
                                <td>{{ $list->name }}</td>
                                <td>{{\Carbon\Carbon::parse($list->start_date)->format('M d,yy g:i A')}}</td>
                                <td>{{\Carbon\Carbon::parse($list->end_date)->format('M d,yy g:i A')}}</td>
                                <td><a href='javascript:void(0)' data-id ="{{$list->url}}" class="btn btn-sm btn-primary attendeeJoin form-control"  id="">Join</a></td>


                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <div class="card bg-light">
                        <div class="card-body">
                            <div class="card">
                                <div class="card-body" style="background: #fff8a0;">
                                    <div class="col-md-7">
                                        <p class="text-danger m-0">We're sorry,you dont have any rooms.</p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                    {{--   Paginaation--}}
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-5">
                            {{$roomList->links()}}
                        </div>
                    </div>
            </section>

        </div>



    </div>

@endsection

@section('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.attendeeJoin').on('click',function () {
                // e.preventDefault();
                let meeting = $(this).data('id');
                $.ajax({
                    type:'POST',
                    url:'{{route("AuthAttendeeJoin")}}',
                    datatype:'json',
                    data:{
                        meeting:meeting,
                        "_token":"{{csrf_token()}}"
                    },success:function (data) {

                        let button ='<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times; </span></button>';

                        if (data.notStart)
                        {
                            $('.errorClass').append(button+'This room not started please contact meeting Owner or try later.');

                            $('.errorClass').show();
                        }
                        if (data.full)
                        {
                            $('.errorClass').empty().append(button+'This room is full  try later.');

                            $('.errorClass').show();
                        }
                        if (data.url)
                        {
                            window.location =data.url;
                        }

                    },

                });


            });

        });


    </script>
@stop
