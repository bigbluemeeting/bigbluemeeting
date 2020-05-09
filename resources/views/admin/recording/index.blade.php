@extends('admin.layouts.app')

@section('pagename', $pageName)

@section('content')
    <div class="title-block">
        <h3 class="title"> {{ $pageName }} </h3>
    </div>

    <div class="col-lg-12">



        <div class="card card-block sameheight-item">


            <section class="example">
                @if (count($recordingList) > 0)
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>

                            <th>Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Participant</th>
                            <th>Breakout</th>
                            <th>State</th>
                            <th>Recording</th>

                        </tr>
                        </thead>
                        <tbody>


                        @foreach($recordingList as $list)
                            <tr>
                                <td>{{$list->name}}</td>
                                <td>{{date("F jS, Y", strtotime($list->startTime))}}</td>
                                <td>{{date("F jS, Y", strtotime($list->endTime))}}</td>
                                <td>{{$list->participants}}</td>
                                <td>{{!$list->isBreakout?'Yes':'No'}}</td>
                                <td>{{ucwords($list->state)}}</td>
                                <td><a href="{{$list->playback->format->url}}">Recording</a></td>

                            </tr>
                        @endforeach
                        @else
                            No Recording Found.
                        @endif

                        </tbody>
                    </table>

            </section>


        </div>

        <div class="row">
            <div class="col-sm-6 col-sm-offset-5">
                {{$recordingList->links()}}
            </div>
        </div>



    </div>

@endsection


@section('javascript')
    <script>
        window.route_mass_crud_entries_destroy = '{{ route('admin::users.mass_destroy') }}';
    </script>
@endsection