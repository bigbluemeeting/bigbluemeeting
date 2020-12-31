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

            {!! Form::open(['method' => 'POST', 'route' => ['plans.store'], 'class'=>'form-horizontal']) !!}

            <div class="form-group">
                {!! Form::label('plan_name', 'Name *', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-12">
                    {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    @if($errors->has('title'))
                        <span class="text-danger">
                            {{ $errors->first('title') }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('participants_total', 'Participants Total *', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-12">
                    {!! Form::text('participants_total', old('participants_total'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    @if($errors->has('participants_total'))
                        <span class="text-danger">
                            {{ $errors->first('participants_total') }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('participants_per_meeting', 'Participants Per Meeting *', ['class' => 'col-sm-5 control-label']) !!}
                <div class="col-sm-12">
                    {!! Form::text('participants_per_meeting', old('participants_per_meeting'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    @if($errors->has('participants_per_meeting'))
                        <span class="text-danger">
                            {{ $errors->first('participants_per_meeting') }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('moderators_per_meeting', 'Moderators Per Meeting *', ['class' => 'col-sm-5 control-label']) !!}
                <div class="col-sm-12">
                    {!! Form::text('moderators_per_meeting',  old('moderators_per_meeting'),['class' => 'form-control', 'placeholder' => '']) !!}
                    @if($errors->has('moderators_per_meeting'))
                        <span class="text-danger">
                            {{ $errors->first('moderators_per_meeting') }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('webcams', 'Web Cams *', ['class' => 'col-sm-5 control-label']) !!}
                <div class="col-sm-12">
                    {!! Form::text('webcams',  old('webcams'),['class' => 'form-control', 'placeholder' => '']) !!}
                    @if($errors->has('webcams'))
                        <span class="text-danger">
                            {{ $errors->first('webcams') }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('billing_frequency_interval', 'Billing Frequency Interval *', ['class' => 'col-sm-5 control-label']) !!}
                <div class="col-sm-12">
                    {!!Form::select('billing_frequency_interval',[''=>'--Choose Billing Interval--']+$billing_frequency_interval,null, ['class'=>'form-control'])!!}

                    @if($errors->has('billing_frequency_interval'))
                        <span class="text-danger">
                            {{ $errors->first('billing_frequency_interval') }}
                        </span>
                    @endif
                </div>
            </div>


            <div class="form-group">
                {!! Form::label('billing_frequency_interval_count', 'Billing Frequency Interval Count *', ['class' => 'col-sm-5 control-label']) !!}
                <div class="col-sm-12">
                    {!! Form::text('billing_frequency_interval_count',  old('billing_frequency_interval_count'),['class' => 'form-control', 'placeholder' => '']) !!}
                    @if($errors->has('billing_frequency_interval_count'))
                        <span class="text-danger">
                            {{ $errors->first('billing_frequency_interval_count') }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('minutes', 'Minutes *', ['class' => 'col-sm-5 control-label']) !!}
                <div class="col-sm-12">
                    {!! Form::text('minutes',  old('minutes'),['class' => 'form-control', 'placeholder' => '']) !!}
                    @if($errors->has('minutes'))
                        <span class="text-danger">
                            {{ $errors->first('minutes') }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('strip_plan_id', 'Strip Plan Id *', ['class' => 'col-sm-5 control-label']) !!}
                <div class="col-sm-12">
                    {!! Form::text('strip_plan_id',  old('strip_plan_id'),['class' => 'form-control', 'placeholder' => '']) !!}
                    @if($errors->has('strip_plan_id'))
                        <span class="text-danger">
                            {{ $errors->first('strip_plan_id') }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('plan_type', 'Plan Type *', ['class' => 'col-sm-5 control-label']) !!}
                <div class="col-sm-12">
                    {!!Form::select('plan_type',$plan_type,null, ['class'=>'form-control'])!!}

                @if($errors->has('plan_type'))
                        <span class="text-danger">
                            {{ $errors->first('plan_type') }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('enable', 'Enabled *', ['class' => 'col-sm-5 control-label']) !!}
                <div class="col-sm-12">
                    {!!Form::select('enable',$enabled,null, ['class'=>'form-control'])!!}

                @if($errors->has('enable'))
                        <span class="text-danger">
                            {{ $errors->first('enable') }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('recording_Size', 'Recording Size *', ['class' => 'col-sm-5 control-label']) !!}
                <div class="col-sm-12">
                    {!! Form::text('recording_Size',  old('strip_plan_id'),['class' => 'form-control', 'placeholder' => '']) !!}

                @if($errors->has('recording_Size'))
                        <span class="text-danger">
                            {{ $errors->first('recording_Size') }}
                        </span>
                    @endif
                </div>
            </div>


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
