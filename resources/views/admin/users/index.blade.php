@extends('admin.layouts.app')

@section('pagename', $pageName)

@section('content')
    <div class="title-block">
        <h3 class="title"> {{ $pageName }} </h3>
    </div>

    <div class="row">

        <div class="col-lg-5">

            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                @if(Session::has($msg))
                    <div class="alert alert-{{ $msg }}">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{ session($msg) }}
                    </div>
                @endif
            @endforeach
            <create-users User-roles="{{$roles}}" form-route="{{route('admin::users.store')}}"></create-users>

        </div>
        <div class="col-lg-7">

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
                    <a href="{{ route('admin::users.add') }}"><button type="button" class="btn btn-pill-right btn-primary">Add New User</button></a>
                </div>

                <section class="example">
                   <users-list user-list="{{route('admin::userList')}}" user-role="{{route('admin::userRoles',':id')}}"></users-list>
                </section>

            </div>

            {{--   Paginaation--}}
            <div class="row">
                <div class="col-sm-6 col-sm-offset-5">
                    {{$users->links()}}
                </div>
            </div>
        </div>

    </div>



@endsection


@section('script')
    <script src="{{ asset('js/app.js') }}"></script>
@stop
@section('javascript')

    <script>

        window.route_mass_crud_entries_destroy = '{{ route('admin::users.mass_destroy') }}';
    </script>
@endsection