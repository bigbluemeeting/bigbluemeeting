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

            {!! Form::model($user, ['method' => 'PUT', 'route' => ['admin::users.update', $user->id], 'class'=>'form-horizontal']) !!}

            <div class="form-group {{ ($errors->has('name'))?'has-error':'' }}">
                {!! Form::label('name', 'Name *', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-12">
                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    @if($errors->has('name'))
                        <span class="has-error">
                            {{ $errors->first('name') }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group {{ ($errors->has('username'))?'has-error':'' }}">
                {!! Form::label('username', 'Username *', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-12">
                    {!! Form::text('username', old('username'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    @if($errors->has('username'))
                        <span class="has-error">
                            {{ $errors->first('username') }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group {{ ($errors->has('email'))?'has-error':'' }}">
                {!! Form::label('email', 'Email*', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-12">
                    {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    @if($errors->has('email'))
                        <span class="has-error">
                            {{ $errors->first('email') }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group {{ ($errors->has('password'))?'has-error':'' }}">
                {!! Form::label('password', 'Password', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-12">
                    {!! Form::password('password', ['class' => 'form-control', 'placeholder' => '']) !!}
                    @if($errors->has('password'))
                        <span class="has-error">
                            {{ $errors->first('password') }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group {{ ($errors->has('roles'))?'has-error':'' }}">
                {!! Form::label('roles', 'Roles*', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-12">
                    {!! Form::select('roles[]', $roles, old('roles') ? old('role') : $user->roles()->pluck('name', 'name'), ['class' => 'form-control select2', 'multiple' => 'multiple', 'required' => '']) !!}
                    @if($errors->has('roles'))
                        <span class="has-error">
                            {{ $errors->first('roles') }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-sm-12 text-center">
                    {!! Form::submit('Save', ['class' => 'btn btn-primary btn-lg']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection
