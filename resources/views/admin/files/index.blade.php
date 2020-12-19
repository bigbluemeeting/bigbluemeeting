@extends('admin.layouts.app')

@section('pagename', $pageName)
@section('css')


    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/blueimp-gallery/2.27.1/css/blueimp-gallery.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.19.1/css/jquery.fileupload.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.19.1/css/jquery.fileupload-ui.min.css">

    <style>
        .table th {
            border: none !important;
            border-bottom: none !important;

        }

    </style>
@stop
@section('content')

    <div class="container-fluid">
        <h5><i class="fa fa-upload"></i>&nbsp;&nbsp;{{$pageName}}</h5>
    </div>


    @error('rooms')
    <div class="col-lg-12 mt-4">
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{$message }}
        </div>
    </div>
    @enderror

    <div class="col-lg-12 mt-4">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has($msg))
                <div class="alert alert-{{ $msg }}">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {{ session($msg) }}
                </div>
            @endif
        @endforeach
    </div>
    @error('meetings')
    <div class="col-lg-12 mt-4">
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{$message }}
        </div>
    </div>
    @enderror

    <div class="main-container">
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card bg-white">
                    <div class="card-body">
                        <form id="fileupload" action="{{ route('files.store') }}" method="post" enctype="multipart/form-data" file="true">
                            <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                            <div class="row fileupload-buttonbar">
                                <div class="col-lg-7">
                                {{--                                <input type="hidden" name="meeting" value="{{$meeting->id}}">--}}
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
                                    <button type="reset" class="btn btn-warning cancel  text-white">
                                        <i class="fa fa-ban"></i>
                                        <span class="">Cancel upload</span>
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
                            <div class="table-responsive mt-3">
                                <table role="presentation" class="table inner-table mt-2">
                                    <tbody class="files">

                                    </tbody>
                                </table>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <div class="container-fluid mt-2">
            <h5><i class="fa fa-files-o" style="color: black"></i>&nbsp;&nbsp Your Files</h5>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card bg-white">
                    <div class="table-responsive">
                    <div class="card-body">
                        <table class="table table-hover" id="table">
                                <thead>
                                @if(count($files)>0)
                                    <tr>
                                        <th>File</th>
                                        <th>Date</th>
                                        <th >Mime</th>
                                        <th>Size</th>

                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($files as $file)
                                    <tr class="row-data-{{$file->id}}">
                                        <td><a href="{{\App\Files::Folder.$file->name}}">{{$file->name}}</a></td>
                                        <td width="200px">{{\Carbon\Carbon::parse($file->upload_date)->format('Y-m-d h:m A')}}</td>
                                        <td>{{$file->type}}</td>
                                        <td  width="100px">{{ \App\Helpers\Helper::formatBytes($file->size)}}</td>

                                        <td>

                                            <span href=""  class="btn btn-sm btn-info-outline btnAddRoom" data-task="{{$file->name}}" data-item = {{encrypt($file->id)}} >
                                                <i class="fa fa-plus"></i> Add to Meeting
                                            </span>
                                        </td>
                                        <td>
                                           <a href="{{route('setDefault',$file->id)}}" class="btn btn-sm btn-secondary boxes">
                                               <i class="fa {{$file->setDefault? 'fa-check-square text-danger':'fa-square'}}"></i>{{ $file->setDefault ? ' Set Default':' Set as Default'}}
                                           </a>
                                        </td>
                                        <td>
                                            <span href=""  class="btn btn-sm btn-danger-outline btnDeleteConfirm"
                                                  data-item = {{$file->id}}>
                                                <i class="fa fa-trash"></i> Delete
                                            </span>
                                        </td>

                                    </tr>
                                @endforeach
                                </tbody>
                                @else
                                <div class="card" style="background: #fff8a0">
                                    <div class="card-body">

                                                <div class="col-md-12">
                                                    <p class="text-danger m-0">We're sorry, you don't have any files.</p>
                                                </div>
                                                <div class="col-md-12">
                                                    <p class="text-danger pt-1">To upload new files, press the "Add Files" button.</p>
                                                </div>

                                    </div>
                                </div>
                                @endif
                            </table>
                        </div>
                        <div class="col-sm-6 col-sm-offset-5 ml-3 paginate">
                            {{$files->links()}}
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>




    <div id="roomFilesAddModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card-title">
                        <h5 class="text-center d-inline-block roomHeader"></h5>
                    </div>

                    <hr>

                    {!! Form::open(['method' => 'Post','route' => ['addFileToRoom'],'class'=>'form-horizontal']) !!}

                    <div class="form-group">
                        <input type="hidden" class="room-file-name" value="" name="file">
                        <lable for="rooms">Meetings</lable>
{{--                        {!!Form::select('rooms',[''=>'Choose Option']+$rooms,null, ['class'=>'form-control'])!!}--}}
                        <select name="rooms" id="" class="form-control mt-2">
                            <option value="">Choose Meeting</option>
                            @foreach($rooms as $room)
                                <option value="{{encrypt($room->id)}}">{{$room->name}}</option>
                            @endforeach

                        </select>
                    </div>
                    <hr>
                    <div class="form-group">
                        {!! Form::submit('Add file to Room ',['class'=>'btn btn-info']) !!}
                    </div>

                    {!! Form::close() !!}


                </div>

            </div>

        </div>
    </div>


        {{--  Add Files to Meeting Modal  --}}

    <div id="meetingFilesAddModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card-title">
                        <h5 class="text-center d-inline-block meetingHeader"></h5>
                    </div>

                    <hr>

                    {!! Form::open(['method' => 'Post','route' => ['addFileToMeeting'],'class'=>'form-horizontal']) !!}

                    <div class="form-group">
                        <input type="hidden" class="meeting-file-name" value="" name="file">
                        <lable for="rooms">Rooms</lable>
                        <select name="meetings" id="" class="form-control mt-2">
                            <option value="">Choose Room</option>
                            @foreach($meetings as $meeting)
                                <option value="{{encrypt($meeting->id)}}">{{$meeting->name}}</option>
                            @endforeach

                        </select>
                    </div>
                    <hr>
                    <div class="form-group">
                        {!! Form::submit('Add file to Meeting ',['class'=>'btn btn-primary']) !!}
                    </div>

                    {!! Form::close() !!}


                </div>

            </div>

        </div>
    </div>



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
{{--    </div>--}}

@stop

@section('script')


                <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
                <script>
                    action =  "{{\Illuminate\Support\Facades\URL::to('files')}}/:id";
                    currentUrl ="{{url()->current()}}";
                </script>
                <script src="{{asset('js/bbb-custom.js')}}"></script>



@stop

@section('js')

            <script src="https://momentjs.com/downloads/moment-with-locales.js"></script>
            @include('_partials.main_files_template')

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
{{--           --}}
            <script src="{{asset('js/fileUpload.js')}}"></script>


@stop

