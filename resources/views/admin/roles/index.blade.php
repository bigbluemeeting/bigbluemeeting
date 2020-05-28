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
            <div class="title-block">
                <a href="{{ route('admin::roles.add') }}"><button type="button" class="btn btn-pill-right btn-primary">Add New Role</button></a>
            </div>

            <section class="example">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Permissions</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        @if (count($roles) > 0)
                            @foreach($roles as $role)
                                <tr>
                                    <td>{{ $role->id }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>
                                        @foreach ($role->permissions()->pluck('name') as $permission)
                                            <span class="badge badge-danger">{{ $permission }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <a href="{{ route('admin::roles.edit',[$role->id]) }}" class="btn btn-sm btn-info">Edit</a>
                                        {!! Form::open(array(
                                            'style' => 'display: inline-block;',
                                            'method' => 'DELETE',
                                            'onsubmit' => "return confirm('Are you sure do you want to delete?');",
                                            'route' => ['admin::roles.destroy', $role->id])) !!}
                                        {!! Form::submit('Delete', array('class' => 'btn btn-sm btn-danger')) !!}
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            No Users Found.
                        @endif

                        </tbody>
                    </table>
                </div>
            </section>

        </div>
    </div>

@endsection


@section('javascript')
    <script>
        window.route_mass_crud_entries_destroy = '{{ route('admin::roles.mass_destroy') }}';
    </script>
@endsection