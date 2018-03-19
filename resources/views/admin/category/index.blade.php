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
                    <a href="{{ route('admin::categories.create') }}"><button type="button" class="btn btn-pill-right btn-primary">Add New Category</button></a>
                </div>



            </div>

    </div>

@endsection