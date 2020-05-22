
@extends('public.layouts.app')
@section('pagename', $pageName)
@section('css')
    <style>
        #overlay{
            position: fixed;
            top: 0;
            z-index: 100;
            width: 80%;
            height:100%;
            display: none;
            background: rgba(0,0,0,0.6);
            margin-left: -6px;
        }
        .cv-spinner {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .newspinner {
            width: 100px;
            height: 100px;
            border: 4px #ddd solid;
            border-top: 4px #2e93e6 solid;
            border-radius: 50%;
            animation: sp-anime 0.8s infinite linear;
        }
        @keyframes sp-anime {
            100% {
                transform: rotate(360deg);
            }
        }
        .is-hide{
            display:none;
        }
    </style>
@stop
@section('content')
    <div id="overlay">


        <div class="cv-spinner">

            <span class="newspinner">
            </span>
        </div>
    </div>
    <div class="title-block">
        <h4> You have been invited to join</h4>
        <h2>
            {{ $pageName }}'s Meeting
        </h2>
    </div>



    <!-- Default form subscription -->
    <div class="container">
        <div class="row">

        </div>
    </div>




    @if($room->all_join_moderator)
        <form action="{{route('attendeeJoinAsModerator')}}" method="post">
            @elseif($room->anyone_can_start)
                <form action="{{route('attendeeStartRoom')}}" method="post">
            @else
                <form class="text-center p-5"   action="{{route('meetingAttendeesJoin')}}" method="Post" id="frm">
                    @endif

        @csrf

        <div class="col-sm-6">
            <!-- Name -->

            <span class="has-error text-danger" id="error">

        </span>

            <input type="text" name="name" class="form-control mb-4 text-center" placeholder="Enter Your Name">
            <input type="hidden" value="{{encrypt($room->url)}}" name="room">

            <!-- Email -->
        </div>
        <div class="col-sm-2">


            <!-- Sign in button -->

            <button class="btn btn-info btn-block" type="submit">
               @if($room->all_join_moderator)
                Start
                @elseif($room->anyone_can_start)
                   Start
                   @else
                    Join
                   @endif

            </button>
        </div>

    </form>


    <!-- Sign in button -->




    <!-- Default form subscription -->

    {{--Table For Meetings List--}}




@endsection
@section('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>
        $(document).ready(function () {

            $('#access_frm').on('submit',function (e) {
                e.preventDefault();
               let frmData = $(this).serialize();

               $.post('{{route("accessCodeResult")}}',frmData,function (data) {
                   if (data.result)
                   {
                       $('#frm').show();
                       $('#access_frm').hide();
                   }
               })

            })

            $('#frm').on('submit',function (e) {

                e.preventDefault();
                let frmData =$(this).serialize();
                setInterval(function () {
                    $.post('{{route("meetingAttendeesJoin")}}',frmData,
                        function (data,xhrStatus,xhr) {
                            if (data.error)
                            {
                                $('#error').html(data.error[0]);
                            }
                            if (data.notStart)
                            {
                                $("#overlay").fadeIn(300);
                            }
                            if (data.full)
                            {
                                $("#overlay").fadeIn(300);
                            }
                            if (data.url)
                            {
                                window.location =data.url;
                            }
                        });
                },1000);
            });

        });


    </script>
@stop
