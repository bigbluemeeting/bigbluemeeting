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

            {!! Form::open(['method' => 'POST', 'route' => ['admin::attendees.store'], 'class'=>'form-horizontal']) !!}


            <div class="form-group {{ ($errors->has('email'))?'has-error':'' }}">
                {!! Form::label('email', 'Email *', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-12">
                    {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    @if($errors->has('email'))
                        <span class="text-danger">
                            {{ $errors->first('email') }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group">

                {!! Form::label('meetings', 'Meetings *', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-12">
                {!!Form::select('meeting_id',[''=>'Choose Option']+$meetingsList,null, ['class'=>'form-control'])!!}
                    @if($errors->has('meeting_id'))
                        <span class="has-error text-danger">
                            {{ $errors->first('meeting_id') }}
                        </span>
                    @endif
                </div>



            </div>

{{--            <div class="form-group {{ ($errors->has('roles'))?'has-error':'' }}">--}}
{{--                {!! Form::label('roles', 'Roles *', ['class' => 'col-sm-2 control-label']) !!}--}}
{{--                <div class="col-sm-12">--}}
{{--                    {!! Form::select('roles[]', $roles, old('roles'), ['class' => 'form-control select2', 'multiple' => 'multiple', 'required' => '']) !!}--}}
{{--                    @if($errors->has('roles'))--}}
{{--                        <span class="has-error">--}}
{{--                            {{ $errors->first('roles') }}--}}
{{--                        </span>--}}
{{--                    @endif--}}
{{--                </div>--}}
{{--            </div>--}}

            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-sm-12 text-center">
                    {!! Form::submit('Save', ['class' => 'btn btn-primary btn-large']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection