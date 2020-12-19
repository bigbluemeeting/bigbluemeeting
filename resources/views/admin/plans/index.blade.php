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
                <a href="{{ route('plans.create') }}"><button type="button" class="btn btn-pill-right btn-primary">Add New Plan</button></a>
            </div>

            <section class="plans" id="plans">
                <div class="table-responsive">
                    <table class="table  table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>PT</th>
                            <th>PPM</th>
                            <th>MPM</th>
                            <th>WC</th>
                            <th>BFI</th>
                            <th>BFIC</th>
                            <th>Minutes</th>
                            <th>SPId</th>
                            <th>PT</th>
                            <th>Enable</th>
                            <th>Recording Size</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        @if (count($plans) > 0)
                            @foreach($plans as $plan)
                                <tr>
                                    <td>{{$plan->id }}</td>
                                    <td>{{$plan->title }}</td>
                                    <td>{{$plan->participants_total }}</td>
                                    <td>{{$plan->participants_per_meeting}}</td>
                                    <td>{{$plan->moderators_per_meeting}}</td>
                                    <td>{{$plan->webcams}}</td>
                                    <td>{{ucwords($plan->billing_frequency_interval)}}</td>
                                    <td>{{$plan->billing_frequency_interval_count}}</td>
                                    <td>{{$plan->minutes}}</td>
                                    <td>{{$plan->strip_plan_id}}</td>
                                    <td>{{ucwords($plan->plan_type)}}</td>
                                    <td>{{$plan->enable==1 ?'Yes' :'No'}}</td>
                                    <td>{{$plan->recording_Size}}</td>

                                    <td>
                                        <a href="{{ route('plans.edit',[$plan->id]) }}" class="btn btn-sm btn-info-outline"><i class="fa fa-edit"></i> Edit</a>
                                        |
                                        <span href="javascript:;" data-toggle="modal"  data-item = {{$plan->id}}
                                                data-target="#DeleteModal" class="btn btn-sm btn-danger-outline btnDeleteConfirm"><i class="fa fa-trash"></i> Delete</span>

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
            {{--  Delete Model  --}}
            <div id="DeleteModal" class="modal fade text-danger" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <form action="" id="deleteForm" method="post">
                        <div class="modal-content">
                            <div class="modal-header bg-danger">

                                <h4 class="modal-title text-center">DELETE CONFIRMATION</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <p class="text-center">Are You Sure Want To Delete ?</p>
                            </div>
                            <div class="modal-footer">

                                <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
                                <button type="submit" name="" class="btn btn-danger btnDelete" data-dismiss="modal" >Yes, Delete</button>

                            </div>
                        </div>
                    </form>
                </div>
            </div>


            {{--   Paginaation--}}
        <div class="row">
            <div class="col-sm-6 col-sm-offset-5">
                {{$plans->links()}}
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        action =  "{{\Illuminate\Support\Facades\URL::to('plans')}}/:id";

        $('.btnDeleteConfirm').on('click',function () {


            id = $(this).data('item');
            deleteData(id)
        });
        function deleteData(id)
        {
            url = action.replace(':id', id);
            $("#deleteForm").prop('action', url);
        }
        $('.btnDelete').on('click',function () {
            $("#deleteForm").submit();
        });
    </script>

@stop
