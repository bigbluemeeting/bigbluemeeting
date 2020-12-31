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
            <create-users update-user="{{route('admin::users.update',':id')}}" User-roles="{{$roles}}" form-route="{{route('admin::users.store')}}"></create-users update-user>

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
                <section class="users">
                   <users-list user-delete="{{route('admin::users.destroy',':id')}}" user-list="{{route('admin::userList')}}" user-edit="{{route('admin::users.edit',':id')}}" ></users-list>
                </section>

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
