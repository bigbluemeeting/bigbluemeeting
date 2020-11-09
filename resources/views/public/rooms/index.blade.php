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
                            </div>

                        </div>
                    </div>
                    <section class="example">
                        <upcoming-meetings meeting-route="{{ route('meetings.store') }}" get-meeting-route="{{route('upComingMeetings')}}" single-meeting-route="{{ route('meetings.edit',':id') }}" update-meeting-route="{{route('meetings.update',':id')}}" ></upcoming-meetings>
                    </section>

                </div>
            </div>

        </div>
    </div>

    <delete-modal delete-route="{{route('deleteMeetings')}}"></delete-modal>


    <div class="container-fluid mt-3">
        <h5>Past Meetings</h5>
    </div>

    <div class="row">
        <div class="col-lg-12">


            <div class="card card-block sameheight-item">
                <section class="example">
                    <past-meetings></past-meetings>
                </section>
            </div>


        </div>
    </div>

@endsection


@section('script')
    <script type="application/javascript" src="https://momentjs.com/downloads/moment-with-locales.js"></script>

    <script src="{{ asset('js/app.js') }}"></script>
    <script type="application/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script type="application/javascript"  src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>

    <script type="application/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>

    <script type="application/javascript">
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
@stop

@section('js')

    <script type="application/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>

    <script type="application/javascript">
        url = '{{URL::to('meetings/:id/edit')}}';
        action =  "{{\Illuminate\Support\Facades\URL::to('meetings')}}/:id";
    </script>
    <script type="application/javascript" src="{{asset('js/rooms.js?version=')}}{{time()}} "></script>

@stop



