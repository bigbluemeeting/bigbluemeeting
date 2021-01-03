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

            <section class="invited-meetings">

                @if ($roomsList > 0)
                    <invited-meetings-list room-route="{{route('getInvitedMeetings')}}"></invited-meetings-list>
                @else
                    <div class="card bg-light">
                        <div class="card-body">
                            <div class="col-md-12">
                                <p class="text-danger m-0"><?= __("We're sorry, you have not been invited to any meetings yet."); ?></p>
                            </div>
                        </div>
                 @endif
            </section>

        </div>



    </div>

@endsection

@section('script')
    <script type="application/javascript" src="https://momentjs.com/downloads/moment-with-locales.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
         var attendeeJoinUrl = '{{route("AuthAttendeeJoin")}}';
         var csrf = '{{csrf_token()}}';
    </script>
    <script src="{{asset('js/bbb-custom.js')}}"></script>

@stop
