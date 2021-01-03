@extends('admin.layouts.app')

@section('pagename', $pageName)
@section('css')
    <link rel="stylesheet" href="{{asset('css/ip.css')}}">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/blueimp-gallery/2.27.1/css/blueimp-gallery.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.19.1/css/jquery.fileupload.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.19.1/css/jquery.fileupload-ui.min.css">
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


                 <invite-participants meeting-attendee-remove="{{route('deleteAttendee')}}" meeting-attendee={{route("roomAttendees")}} meeting-url="{{$url}}"  file-route="{{route('files.store')}}" participant-route="{{route('addParticipantDetails',':url')}}"></invite-participants>

                    <div class="container-fluid" >

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
                                </thead>
                            </table>

                         <meetings-files delete-file-route="{{route('deleteFile')}}" file-route="{{route('meetingFiles',':url')}}" meeting-url="{{$url}}"></meetings-files>

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
                </div>
            </div>
        </div>
    </div>


@stop

@section('script')
    <script>
        var csrf = '{{csrf_token()}}';
    </script>
    <script type="application/javascript" src="https://momentjs.com/downloads/moment-with-locales.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
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

