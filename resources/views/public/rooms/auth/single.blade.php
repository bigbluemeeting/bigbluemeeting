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

        <div class="card card-block sameheight-item">

            {!! Form::open(['method' => 'POST', 'route' => ['join'], 'class'=>'form-horizontal']) !!}
            <div class="form-group {{ ($errors->has('email'))?'has-error':'' }}">
                {!! Form::label('url', 'Invite Participants', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('url', value(url()->current()), ['class' => 'form-control', 'placeholder' => '']) !!}
                    @if($errors->has('email'))
                        <span class="text-danger">
                            {{ $errors->first('email') }}
                        </span>
                    @endif
                </div>

            </div>
            <input type="hidden" value="{{encrypt($room->url)}}" name="room">
            <input type="hidden" name="username" value="{{encrypt($room->user->username)}}">


            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-sm-6 text-center">
                    {!! Form::submit('Start Meeting', ['class' => 'btn btn-info btn-large']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection