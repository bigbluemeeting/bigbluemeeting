@extends('admin.layouts.app')

@section('pagename', $pageName)

@section('content')
    <div class="title-block">
        <h4 class="title"><i class="fa fa-film"></i> {{ $pageName }} </h4>
    </div>
    @if(Gate::check('moderate') || Gate::check('users_manage') || Gate::check('master_manage'))

        <div class="container-fluid">

        <h5>My Meeting Recordings</h5>
        <div class="row" id="error">
            <div class="col-md-12">
                @include('includes.form-errors')
            </div>
        </div>
    </div>
        @endif

    <div class="col-lg-12">

        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has($msg))
                <div class="alert alert-{{ $msg }}">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {{ session($msg) }}
                </div>
            @endif
        @endforeach
        @if(Gate::check('moderate') || Gate::check('users_manage') || Gate::check('master_manage'))


        <div class="card card-block sameheight-item mt-2">

            <section class="recordings" id="recordings">
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-5">
                        {{$recordingList->links()}}
                    </div>
                </div>
                @if (count($recordingList) > 0)
                    <div class="table-responsive">
                        <table class="table  table-hover">
                            <thead>
                            <tr>

                                <th>Name</th>
                                <th>Playback</th>
                                <th>Published</th>
                                <th>Delete</th>
                                <th>Length</th>
                                <th>Users</th>
                                <th>Format</th>
                                <th>Started</th>
                                <th>Ended</th>

                            </tr>
                            </thead>
                            <tbody>

                            @foreach($recordingList as  $list)
                                <tr>
                                    <td>{{$list->name}}</td>
                                    <td><a class="btn btn-sm btn-info" href="{{$list->playback->format->url}}">Watch</a></td>
                                    <td>

                                        {!! Form::open(array(
                                                             'style' => 'display: inline-block;',
                                                             'method' => 'POST',
                                                             'route' => 'admin::publishedRecording')) !!}
                                        <input type="hidden" name="recording" value="{{$list->recordID}}">
                                        <input type="hidden" value ="{{$list->published == 'true' ? 0 :1 }}" name="published">
                                        {!! Form::submit($list->published == 'true' ? 'UnPublished' : ' Published', array('class' => 'btn btn-sm btn-dark')) !!}
                                        {!! Form::close() !!}
                                    </td>
                                    <td>
                                        {!! Form::open(array(
                                                 'style' => 'display: inline-block;',
                                                 'method' => 'DELETE',
                                                 'onsubmit' => "return confirm('Are you sure do you want to delete?');",
                                                 'route' => ['admin::recordings.destroy', $list->recordID])) !!}
                                        {!! Form::submit('Delete', array('class' => 'btn btn-sm btn-danger ')) !!}
                                   {!! Form::close() !!}
                                    </td>
                                    <td>{{\App\Helpers\Helper::formatBytes($list->rawSize)}}</td>
                                    <td class="text-center">{{$list->participants}}</td>
                                    <td>{{ucwords($list->playback->format->type)}}</td>
                                    @foreach (\App\Room::where('url',$list->metadata->meetingId)->get() as $meeting)
                                        <td>{{\Carbon\Carbon::parse($meeting->start_date)->format('M d,yy g:i A')}}</td>
                                        <td>{{\Carbon\Carbon::parse($meeting->end_date)->format('M d,yy g:i A')}}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                            @else
                                <div class="card bg-light" >
                                    <div class="card-body"  style="background: #fff8a0;">
                                        <div class="col-md-12">
                                            <p class="text-danger m-0">We're sorry, you don't have any recording(s).</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            </tbody>
                        </table>

                    </div>

            </section>
        </div>
        @endif

    {{-- Rooms Recordings List --}}

            <div class="container-fluid mt-4">
                <h5>My Room Recordings</h5>
            </div>
            <div class="col-lg-12">
                    <div class="card card-block sameheight-item mt-3">
                        <section class="room-recordings" id="room-recordings">
                            @if (count($meetingRecordings) > 0)

                                <div class="table-responsive">
                                    <table class="table  table-hover">
                                        <thead>
                                        <th>Name</th>
                                        <th>Playback</th>
                                        <th>Published</th>
                                        <th>Delete</th>
                                        <th>Length</th>
                                        <th>Users</th>
                                        <th>Format</th>
                                        </thead>
                                        <tbody>
                                        @foreach($meetingRecordings as $list)
                                            <tr>
                                                <td>{{$list->name}}</td>
                                                <td><a class="btn btn-sm btn-info" href="{{$list->playback->format->url}}">Watch</a></td>
                                                <td>
                                                    {!! Form::open(array(
                                                              'style' => 'display: inline-block;',
                                                              'method' => 'POST',
                                                              'route' => 'admin::publishedRecording')) !!}
                                                    <input type="hidden" name="recording" value="{{$list->recordID}}">
                                                    <input type="hidden" value ="{{$list->published == 'true' ? 0 :1 }}" name="published">
                                                    {!! Form::submit($list->published == 'true' ? 'UnPublished' : ' Published', array('class' => 'btn btn-sm btn-dark')) !!}
                                                    {!! Form::close() !!}
                                                </td>
                                                <td>
                                                    {!! Form::open(array(
                                                             'style' => 'display: inline-block;',
                                                             'method' => 'DELETE',
                                                             'onsubmit' => "return confirm('Are you sure do you want to delete?');",
                                                             'route' => ['admin::recordings.destroy', $list->recordID])) !!}
                                                    {!! Form::submit('Delete', array('class' => 'btn btn-sm btn-danger')) !!}
                                                    {!! Form::close() !!}
                                                </td>
                                                <td>{{\App\Helpers\Helper::formatBytes($list->rawSize)}}</td>
                                                <td class="text-center">{{$list->participants}}</td>
                                                <td>{{ucwords($list->playback->format->type)}}</td>
                                            </tr>
                                        @endforeach
                                        @else
                                            <div class="card bg-light">
                                                <div class="card-body" style="background: #fff8a0;">
                                                    <div class="col-md-12">
                                                        <p class="text-danger m-0">We're sorry, you don't have any recording(s).</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 col-sm-offset-5">
                                        {{$recordingList->links()}}
                                    </div>
                                </div>
                        </section>
                    </div>

                </div>
    </div>

@endsection


@section('javascript')
    <script>
        window.route_mass_crud_entries_destroy = "{{ route('admin::users.mass_destroy') }}";
    </script>
@endsection
