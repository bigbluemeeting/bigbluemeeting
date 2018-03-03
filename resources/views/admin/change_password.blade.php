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

            {!! Form::open(['method' => 'PATCH', 'route' => ['admin::change_password'], 'class'=>'form-horizontal']) !!}

            <div class="form-group {{ ($errors->has('current_password'))?'has-error':'' }}">
                {!! Form::label('current_password', 'Current password *', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-12">
                    {!! Form::password('current_password', ['class' => 'form-control', 'placeholder' => '']) !!}
                    @if($errors->has('current_password'))
                        <span class="has-error">
                            {{ $errors->first('current_password') }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group {{ ($errors->has('new_password'))?'has-error':'' }}">
                {!! Form::label('new_password', 'New Password *', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-12">
                    {!! Form::password('new_password', ['class' => 'form-control', 'placeholder' => '']) !!}
                    @if($errors->has('new_password'))
                        <span class="has-error">
                            {{ $errors->first('new_password') }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group {{ ($errors->has('new_password_confirmation'))?'has-error':'' }}">
                {!! Form::label('new_password_confirmation', 'Confirm New Password *', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-12">
                    {!! Form::password('new_password_confirmation', ['class' => 'form-control', 'placeholder' => '']) !!}
                    @if($errors->has('new_password_confirmation'))
                        <span class="has-error">
                            {{ $errors->first('new_password_confirmation') }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-sm-12 text-center">
                    {!! Form::submit('Change Password', ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection
