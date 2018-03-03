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

            {!! Form::model($role, ['method' => 'PUT', 'route' => ['admin::roles.update', $role->id], 'class'=>'form-horizontal']) !!}

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


            <div class="form-group {{ ($errors->has('permission'))?'has-error':'' }}">
                {!! Form::label('permission', 'Permissions *', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-12">
                    {!! Form::select('permission[]', $permissions, old('permission') ? old('permission') : $role->permissions()->pluck('name', 'name'), ['class' => 'form-control select2', 'multiple' => 'multiple']) !!}
                    @if($errors->has('permission'))
                        <span class="has-error">
                            {{ $errors->first('permission') }}
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
