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
        .boxes {
            background:#f6f6f6 !important;
        }

        /*Checkboxes styles*/
        input[type="checkbox"] { display: none; }

        input[type="checkbox"] + label {
            display: block;
            position: relative;
            padding-left: 27px;
            margin-bottom: 20px;
            font: 14px/20px 'Open Sans', Arial, sans-serif;
            /*color: #ddd;*/
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }

        input[type="checkbox"] + label:last-child { margin-bottom: 0; }

        input[type="checkbox"] + label:before {
            content: '';
            display: block;
            width: 20px;
            height: 20px;
            border: 2px solid #78BD2E;
            position: absolute;
            left: 0;
            top: 0;
            opacity: .6;
            -webkit-transition: all .12s, border-color .08s;
            transition: all .12s, border-color .08s;
        }

        input[type="checkbox"]:checked + label:before {
            width: 10px;
            top: -5px;
            left: 5px;
            border-radius: 0;
            opacity: 1;
            border-top-color: transparent;
            border-left-color: transparent;
            -webkit-transform: rotate(45deg);
            transform: rotate(45deg);
        }
    </style>
@stop
@section('content')

    <div class="container-fluid">
        <h5><i class="fa fa-users"></i>&nbsp;&nbsp;{{$pageName}}</h5>
    </div>
    <div class="main-container">
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card bg-white">
                    <div class="card-body">
                        <form id="fileupload" action="{{ route('files.store') }}" method="post" enctype="multipart/form-data">
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

        <div class="container-fluid mt-2">
            <h5><i class="fa fa-users"></i>&nbsp;&nbsp;{{$pageName}}</h5>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card bg-white">
                    <div class="card-body">
                        <div class="table-responsive">

                            <table class="table table-hover">
                                <thead>
                                @if(count($files)>0)
                                    <tr>
                                        <th>File</th>
                                        <th>Date</th>
                                        <th>Mime</th>
                                        <th>Size</th>

                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($files as $file)
                                    <tr class="row-data-{{$file->id}}">
                                        <td><a href="{{\App\Files::Folder.$file->name}}">{{$file->name}}</a></td>
                                        <td>{{\Carbon\Carbon::parse($file->upload_date)->format('Y-m-d h:m A')}}</td>
                                        <td>{{$file->type}}</td>
                                        <td>{{ \App\Helpers\Helper::formatBytes($file->size)}}</td>
                                        <td>
                                        <span href="" data-toggle="modal"  data-item = {{$file->id}}
                                                data-target="#DeleteModal" class="btn  btn-primary-outline btnAddMeeting"><i class="fa fa-plus"></i> Add to Meeting</span>
                                        </td>
                                        <td>
                                           <a href="{{route('setDefault',$file->id)}}" class="btn  btn-secondary boxes">

                                                <i class="fa {{$file->setDefault? 'fa-check-square text-danger':'fa-square'}}"></i> Set as Default
                                            </a>
                                        </td>
                                        <td>
                                            <span href="" data-toggle="modal"  data-item = {{$file->id}}
                                                    data-target="#DeleteModal" class="btn btn-danger-outline btnDeleteConfirm"><i class="fa fa-trash"></i> Delete</span>
                                        </td>

                                    </tr>
                                @endforeach
                                </tbody>
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




    {{-- Add Participant Modal   --}}
{{--    <div id="myModal" class="modal fade" role="dialog">--}}
{{--        <div class="modal-dialog modal-dialog-centered">--}}

{{--            <!-- Modal content-->--}}
{{--            <div class="modal-content">--}}

{{--                <div class="modal-body">--}}
{{--                    <div class="card-body p-sm-6">--}}
{{--                        <div class="card-title">--}}
{{--                            <h3 class="text-center">Invite Participants</h3>--}}
{{--                            <h3 class="update-only" style="display:none !important">Room Settings</h3>--}}
{{--                        </div>--}}
{{--                        <div class="alert alert-danger errorClass" style="display: none">--}}
{{--                        </div>--}}
{{--                        <div class="input-icon mb-2">--}}
{{--                            <input id="testInput" >--}}
{{--                        </div>--}}
{{--                        <div class="row">--}}
{{--                            <div class="mt-3 ml-3">--}}
{{--                                <input type="submit" value="Add Participants" class="create-only btn btn-primary btn-block" id="addPar" >--}}
{{--                                <input type="submit" name="commit" value="Update Room" class="update-only btn btn-primary btn-block" data-disable-with="Update Room" style="display:none !important">--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <input type="hidden" id="room" value="{{$meeting->url}}">--}}
{{--                <div class="modal-footer bg-light">--}}
{{--                    <p class="text-primary"><strong> Info ! </strong> Participants need to singup if he's not member of this site. Invitational mail will be sent to his email </p>--}}

{{--                </div>--}}
{{--            </div>--}}

{{--        </div>--}}
{{--    </div>--}}
{{--    --}}{{-- DELETE MODAL   --}}

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

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <script>
    $('.main-container').on('click','.checkbox',function () {
        console.log('sfkjbhsdjfghj')
        {{--url ="{{\Illuminate\Support\Facades\URL::route('setDefault')}}";--}}
        {{--csrf ={{csrf_token()}}--}}
        {{--$.ajax({--}}
        {{--    type:'POST',--}}
        {{--    url:url,--}}
        {{--    datatype:'json',--}}
        {{--    data:{--}}

        {{--        file:$('.checkbox').data('task'),--}}
        {{--        value:$('.checkbox').val(),--}}

        {{--    },success:function (data) {--}}

        {{--    },--}}

        {{--});--}}


    })
    </script>

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
            <script src="{{asset('js/fileUpload.js')}}"></script>


@stop

