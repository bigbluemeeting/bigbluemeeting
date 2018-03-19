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

            {!! Form::open(['method' => 'POST', 'route' => ['admin::categories.store'], 'class'=>'form-horizontal']) !!}

            <div class="form-group {{ ($errors->has('itemname_eng'))?'has-error':'' }}">
                {!! Form::label('itemname_eng', 'Name English*', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-12">
                    {!! Form::text('itemname_eng', old('itemname_eng'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    @if($errors->has('itemname_eng'))
                        <span class="has-error">
                            {{ $errors->first('itemname_eng') }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group {{ ($errors->has('itemname_mal'))?'has-error':'' }}">
                {!! Form::label('itemname_mal', 'Name Malayalam*', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-12">
                    {!! Form::text('itemname_mal', old('itemname_mal'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    @if($errors->has('itemname_mal'))
                        <span class="has-error">
                            {{ $errors->first('itemname_mal') }}
                        </span>
                    @endif
                </div>
            </div>


            <div class="form-group {{ ($errors->has('parent_id'))?'has-error':'' }}">
                {!! Form::label('parent_id', 'Category', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-12">
                    {!! Form::select('parent_id', $categories, old('parent_id'), ['class' => 'form-control']) !!}
                    @if($errors->has('parent_id'))
                        <span class="has-error">
                            {{ $errors->first('parent_id') }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-sm-12 text-center">
                    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection
