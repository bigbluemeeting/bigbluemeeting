@extends('admin.layouts.app')

@section('pagename', $pageName)

@section('content')
    <div class="title-block">
        <h4 class="title"><i class="fa fa-film"></i> {{ $pageName }} </h4>
    </div>
    @if(Gate::check('moderate') || Gate::check('users_manage') || Gate::check('master_manage'))
        <h5>My Meeting Recordings</h5>
    @endif


        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has($msg))
                <div class="alert alert-{{ $msg }}">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {{ session($msg) }}
                </div>
            @endif
        @endforeach
            <div class="card card-block sameheight-item">
                <section id="invited-rooms" class="invited-rooms">
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

                                   <th>{{ __('Name') }}</th>
                                   <th>{{ __('Playback')}}</th>
                                   <th>{{__('State')}}</th>
                                   <th>{{__('Length')}}</th>
                                   <th>{{__('Users')}}</th>
                                   <th>{{__('Format')}}</th>
                                   <th>{{__('Started')}}</th>
                                   <th>{{__('Ended')}}</th>

                               </tr>
                               </thead>
                               <tbody>

                               @foreach($recordingList as  $list)

                                   <tr>
                                       <td>{{$list->name}}</td>
                                       <td><a class="btn btn-sm btn-info" href="{{$list->playback->format->url}}">Watch</a></td>
                                       <td>{{ucwords($list->state)}}</td>
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
                                       <div class="card-body" style="background: #fff8a0;">
                                            <div class="col-md-12">
                                                <p class="text-danger m-0">{{__('We\'re sorry, you don\'t have any recording(s).')}}</p>
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


@endsection


@section('javascript')
    <script>
        window.route_mass_crud_entries_destroy = "{{ route('admin::users.mass_destroy') }}";
    </script>
@endsection
