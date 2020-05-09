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
            @if(Gate::check('moderate') || Gate::check('users_manage') || Gate::check('master_manage'))
            <div class="title-block">
                <a href="{{ route('admin::attendees.index') }}"><button type="button" class="btn btn-pill-right btn-primary">Add New Attendee</button></a>
            </div>
            @endif
            @if (count($attendees) > 0)
            <section class="example">
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>

                        <th>Name</th>
                    </tr>
                    </thead>
                    <tbody>

                        @foreach($attendees as $attendee)
                            <tr>
                                <td>{{ $attendee->email }}</td>

{{--                                <td>--}}
{{--                                    @foreach ($user->roles()->pluck('name') as $permission)--}}
{{--                                        <span class="badge badge-danger">{{ $permission }}</span>--}}
{{--                                    @endforeach--}}
{{--                                </td>--}}
{{--                                <td>--}}
{{--                                    <a href="{{ route('admin::users.edit',[$user->id]) }}" class="btn btn-sm btn-info">Edit</a>--}}
{{--                                    {!! Form::open(array(--}}
{{--                                        'style' => 'display: inline-block;',--}}
{{--                                        'method' => 'DELETE',--}}
{{--                                        'onsubmit' => "return confirm('Are you sure do you want to delete?');",--}}
{{--                                        'route' => ['admin::users.destroy', $user->id])) !!}--}}
{{--                                    {!! Form::submit('Delete', array('class' => 'btn btn-sm btn-danger')) !!}--}}
{{--                                    {!! Form::close() !!}--}}
{{--                                </td>--}}
                            </tr>
                        @endforeach
                    @else
                        No Attendees Found.
                    @endif

                    </tbody>
                </table>
            </section>

        </div>
            <div class="row">
                <div class="col-sm-6 col-sm-offset-5">
                    {{$attendees->links()}}
                </div>
            </div>
    </div>

@endsection


@section('javascript')
    <script>
        window.route_mass_crud_entries_destroy = '{{ route('admin::users.mass_destroy') }}';
    </script>
@endsection