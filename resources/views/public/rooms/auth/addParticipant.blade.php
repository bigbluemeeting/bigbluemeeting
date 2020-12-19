@extends('admin.layouts.app')

@section('pagename', $pageName)
@section('css')
    <link rel="stylesheet" href="{{asset('css/ip.css')}}">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/blueimp-gallery/2.27.1/css/blueimp-gallery.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.19.1/css/jquery.fileupload.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.19.1/css/jquery.fileupload-ui.min.css">

    <style>

    </style>
@stop
@section('content')

    <div class="container-fluid">
        <h5><i class="fa fa-users"></i>&nbsp;&nbsp;<?= __('Meeting Information'); ?></h5>
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
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card bg-white">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped ">
                            <tbody>
                            <tr>
                                <td><?= __('Meeting Name'); ?></td>
                                <td>{{$meeting->name}}</td>

                            </tr>
                            <tr>
                                <td><?= __('Start Time (Local)'); ?></td>
                                <td>{{\Carbon\Carbon::parse($meeting->start_date)->format('M d,yy g:i A')}}</td>

                            </tr>
                            <tr>
                                <td><?= __('End Time (Local)'); ?></td>
                                <td>{{\Carbon\Carbon::parse($meeting->end_date)->format('M d,yy g:i A')}}</td>
                            </tr>
                            <tr>
                                <td><?= __('No. of Participants'); ?></td>
                                <td>{{$meeting->maximum_people}}</td>

                            </tr>

                            <tr>
                                <td><?= __('Recording'); ?></td>
                                <td>{{$meeting->meeting_record ? 'Yes' : 'No'}}</td>
                            </tr>
                            <tr>
                                <td>Description</td>
                                <td>{{$meeting->meeting_description}}</td>
                            </tr>


                            </tbody>
                        </table>
                        <hr >
                        <div class="container mt-3" >
                            <h5><i class="fa fa-user"></i>&nbsp;&nbsp;Meeting Participants</h5>
                        </div>
                        @if(!$pastMeeting)
                        <div class="input-group" >
                            <div class="input-group-prepend">
                                <div class="col-md-11 mt-2">
                                    <span class="create-only btn btn-info btn-block input-group-text" data-toggle="modal" data-target="#myModal" id="createRoom">
                                        <i class="fa fa-plus-circle text-center text-white pr-3">&nbsp; Invite Participant <i class="fa fa-caret-down ml-1"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            @if(count($attendees)>0)
                                <div class="card bg-white m-0">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                        <table class="table ">
                                            <thead >
                                            <tr>
                                                <th>Email</th>
                                                <th>Created At</th>
                                                <th width="200px" class="text-center">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody >
                                            @foreach($attendees as $attendee)
                                                <tr>
                                                    <td>{{$attendee->email}}</td>
                                                    <td>{{$attendee->created_at->diffForHumans()}}</td>
                                                    <td  class="text-center"><a href="{{route('deleteAttendee',$attendee->id)}}" class="btn btn-danger-outline"><i class="fa fa-remove"></i> Remove</a></td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-sm-offset-5">
                                    {{$attendees->links()}}
                                </div>

                            </div>
                            @else
                                <div class="card bg-light">
                                    <div class="card-body" style="background: #fff8a0;">

                                        <div class="col-md-12" >
                                            <p class="text-danger m-0">There are no participants invited to this meeting.</p>

                                            @if(!$pastMeeting)
                                                <p class="text-danger pt-1">To participants to this meeting,click the blue button on the top left.</p>
                                            @else

                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>

                        <div class="container-fluid {{count($attendees) > 0 ? '' :'mt-3' }}">
                            <hr class="m-0">
                        </div>
                        <div class="container mt-3">
                            <h5><i class="fa fa-folder-open"></i>&nbsp;&nbsp;Files</h5>
                        </div>


                        <div class="table-responsive" >
                            <div class="col-md-12">
                                <table class="table table-hover ">
                                    <thead>
                                    @if(!$pastMeeting)
                                    <tr>
                                        <th class="bg-light  form-header" colspan="5">
                                            <form id="fileupload" action="{{ route('files.store') }}" method="post" enctype="multipart/form-data">
                                                <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                                                <div class="row fileupload-buttonbar">
                                                    <div class="col-lg-7">
                                                        <input type="hidden" name="rooms" value="{{$meeting->id}}">
                                                        <!-- The fileinput-button span is used to style the file input field as button -->
                                                        <span class="btn btn-success fileinput-button text-white ">
                                                            <i class="fa fa-plus"></i>
                                                            <span>Add files...</span>
                                                            <input type="file" name="files[]" multiple>
                                                        </span>
                                                        <button type="submit" class="btn btn-primary start">
                                                            <i class="fa fa-upload"></i>
                                                            <span>Start upload</span>
                                                        </button>
                                                        <button type="reset" class="btn btn-warning cancel text-white">
                                                            <i class="fa fa-ban"></i>
                                                            <span>Cancel upload</span>
                                                        </button>
                                                        <span class="fileupload-process"></span>
                                                    </div>
                                                    <!-- The global progress state -->
                                                    <div class="col-lg-5 fileupload-progress fade">
                                                        <!-- The global progress bar -->
                                                        <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                                            <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                                                        </div>
                                                        <!-- The extended global progress state -->
                                                        <div class="progress-extended">&nbsp;</div>
                                                    </div>
                                                </div>
                                                <!-- The table listing the files available for upload/download -->
                                                <table role="presentation" class="table inner-table mt-2">
                                                    <tbody class="files">

                                                    </tbody>
                                                </table>
                                            </form>

                                        </th>
                                    </tr>
                                    @endif
                                    @if(count($files)>0)
                                        <tr>
                                            <th>File</th>
                                            <th>Date</th>
                                            <th>Mime</th>
                                            <th>Size</th>
                                            <td>Action</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($files as $file)
                                        <tr class="row-data-{{$file->id}}">
                                            <td><a href="{{asset(\App\Files::Folder).'/'.$file->name}}">{{$file->name}}</a></td>
                                            <td>{{\Carbon\Carbon::parse($file->upload_date)->format('Y-m-d h:m A')}}</td>
                                            <td>{{$file->type}}</td>
                                            <td>{{ \App\Helpers\Helper::formatBytes($file->size)}}</td>
                                            <td>
                                             <span href="" data-toggle="modal"  data-item = {{$file->id}} data-target="#DeleteModal" class="btn btn-sm btn-danger-outline btnDeleteConfirm"><i class="fa fa-trash"></i> Delete</span>

                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    @endif
                                </table>
                                @if(count($files)==0)

                                    <div class="card bg-light">
                                        <div class="card-body" style="background: #fff8a0;">

                                                    <div class="col-md-7" >
                                                        <p class="text-danger m-0">There are no files added to this meeting.</p>
                                                        @if(!$pastMeeting)
                                                            <p class="text-danger pt-1">To add files to this meeting,click the "Add Files" on the top left.</p>
                                                        @endif
                                                    </div>
                                        </div>
                                    </div>
                                    @endif
                            </div>
                        </div>

                        <div class="col-sm-6 col-sm-offset-5 ml-3 paginate">
                            {{$files->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





        {{-- Add Participant Modal   --}}
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered">

            <!-- Modal content-->
            <div class="modal-content">

                <div class="modal-body">
                    <div class="card-body p-sm-6">
                        <div class="card-title">
                            <h3 class="text-center">Invite Participants</h3>
                            <h3 class="update-only" style="display:none !important">Room Settings</h3>
                        </div>
                        <div class="alert alert-danger errorClass" style="display: none">
                        </div>
                        <div class="input-icon mb-2">
                            <input id="testInput" >
                        </div>
                        <div class="row">
                            <div class="mt-3 ml-3">
                                <input type="submit" value="Add Participants" class="create-only btn btn-primary btn-block" id="addPar" >
                                <input type="submit" name="commit" value="Update Room" class="update-only btn btn-primary btn-block" data-disable-with="Update Room" style="display:none !important">
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="room" value="{{$meeting->url}}">
                <div class="modal-footer bg-light">
                    <p class="text-primary"><strong> Info ! </strong> Participants need to singup if he's not member of this site. Invitational mail will be sent to his email </p>

                </div>
            </div>

        </div>
    </div>
    {{-- DELETE MODAL   --}}

    <div id="DeleteModal" class="modal fade text-danger" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
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

                        <input type="hidden" value="" name="task" class="task-input ">
                        <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
                        <button type="button" name="" class="btn btn-danger btnDelete" data-dismiss="modal">Yes, Delete</button>

                    </div>
                </div>

        </div>
    </div>

@stop

@section('script')

    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>

        var slug = $('#room').val();
        var postUrl = '{{route("roomAttendees")}}';
        var  url =  "{{ route('showDetails', ':slug' )}}";
        var csrf = '{{csrf_token()}}';
        action =  "{{\Illuminate\Support\Facades\URL::to('files')}}/:id";
        currentUrl ="{{url()->current()}}";


    </script>
    <script src="{{asset('js/bbb-custom.js')}}"></script>

@stop

@section('js')

    @include('_partials.x-template')
    <script src="//cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.19.1/js/vendor/jquery.ui.widget.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/blueimp-JavaScript-Templates/3.11.0/js/tmpl.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/blueimp-load-image/2.17.0/load-image.all.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/javascript-canvas-to-blob/3.14.0/js/canvas-to-blob.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/blueimp-gallery/2.27.1/js/jquery.blueimp-gallery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.19.1/js/jquery.iframe-transport.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.19.1/js/jquery.fileupload.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.19.1/js/jquery.fileupload-process.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.19.1/js/jquery.fileupload-image.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.19.1/js/jquery.fileupload-validate.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.19.1/js/jquery.fileupload-ui.min.js"></script>
    <script src="{{asset('js/fileUpload.js')}}"></script>

@stop

