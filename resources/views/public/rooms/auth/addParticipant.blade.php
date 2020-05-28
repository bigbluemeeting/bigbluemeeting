@extends('admin.layouts.app')

@section('pagename', $pageName)
@section('css')
    <style>

        .input-icons i {
            position: absolute;
        }

        .input-icons {
            width: 100%;
            margin-bottom: 10px;
        }
        .input-icon .cursor-pointer
        {
            cursor: pointer;
        }

        .icon {
            padding: 10px;
            min-width: 40px;
        }

        .tags-wrapper {
            background:white;
            overflow:hidden;
            /*width:300px;*/
            /*background-image: -webkit-linear-gradient(top, rgb(238, 238, 238) 1%, white 15%);*/
            /*box-shadow: 0 0 5px rgba(0, 0, 0, .3);*/
        }
        .tags-wrapper ul {
            margin:0px;
            padding:0px;
        }
        .tags-wrapper li {
            float:left;
        }
        .tags-wrapper li.tag {
            font-family:verdana;
            font-size:11px;

            border-radius:3px;
            list-style: none;
            background-clip: padding-box;
            background-color: rgb(228, 228, 228);
            background-image:-webkit-linear-gradient(top, rgb(244, 244, 244) 20%, rgb(240, 240, 240) 50%, rgb(232, 232, 232) 52%, rgb(238, 238, 238) 100%);
            box-shadow: 0 0 2px white inset, 0 1px 0 rgba(0, 0, 0, 0.05);
            color: rgb(51, 51, 51);
            border: 1px solid rgb(170, 170, 170);
            line-height: 13px;
            padding: 1px 3px 3px 5px;
            margin: 3px 0 3px 5px;
        }
        .tags-wrapper li a {
            text-decoration:none;
            color:white;
            padding:2px;
            display:inline-block;
            margin-left:6px;
            color: rgb(51, 51, 51);
        }
        .tags-wrapper li a:hover {
            color:#222;
        }
        .tags-wrapper input {
            display:none;
            border: black;
        }
        .tags-wrapper li.tags-input {
            white-space: nowrap;
            margin: 0;
            padding: 0;
        }
        .tags-wrapper li input {
            display:block;
            background:trasparent;
            outline:none;
            border:none;
            font-size:14px;
            height: auto;
            width:420px;
            margin: 4px;
        }
        .tags-wrapper .autofill-bg {
            position:relative;
            top:4px;
        }

    </style>
@stop
@section('content')

    <div class="container-fluid">
        <h5><i class="fa fa-users"></i>&nbsp;&nbsp;Meeting Information</h5>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card bg-white">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <tbody>
                            <tr>
                                <td>Meeting Name</td>
                                <td>{{$meeting->name}}</td>

                            </tr>
                            <tr>
                                <td>Start Time (Local)</td>
                                <td>{{\Carbon\Carbon::parse($meeting->start_date)->format('M d,yy g:i A')}}</td>

                            </tr>
                            <tr>
                                <td>End Time (Local)</td>
                                <td>{{\Carbon\Carbon::parse($meeting->end_date)->format('M d,yy g:i A')}}</td>
                            </tr>
                            <tr>
                                <td>No. of Participants</td>
                                <td>{{$meeting->maximum_people}}</td>

                            </tr>

                            <tr>
                                <td>Recording</td>
                                <td>{{$meeting->meeting_record ? 'Yes' : 'No'}}</td>
                            </tr>
                            <tr>
                                <td>Description</td>
                                <td>{{$meeting->meeting_description}}</td>
                            </tr>


                            </tbody>
                        </table>
                        <hr>
                        <div class="container-fluid">
                            <h5><i class="fa fa-user"></i>&nbsp;&nbsp;Meeting Participants</h5>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="col-md-11 mt-2">
                                    <span class="create-only btn btn-info btn-block input-group-text" data-toggle="modal" data-target="#myModal"id="createRoom">
                                        <i class="fa fa-plus-circle text-center text-white pr-3">&nbsp; Invite Participant <i class="fa fa-caret-down ml-1"></i></i>
                                    </span>
                                </div>
                            </div>
                            </div>
                        </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card bg-white">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th>Email</th>
                                                <th>Created At</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($attendees as $attendee)
                                                <tr>
                                                    <td>{{$attendee->email}}</td>
                                                    <td>{{$attendee->created_at->diffForHumans()}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
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
{{--                        {!! Form::open(['method' => 'POST', 'route' => ['meetings.store'], 'class'=>'form-horizontal','id'=>'frm']) !!}--}}

                        <div class="input-icon mb-2">
{{--                            <span class="input-icons">--}}
{{--                                <i class="fa fa-envelope icon ml-2"></i>--}}
{{--                            </span>--}}
{{--                            class="form-control text-center" value="" placeholder="Enter Participants Email..." autocomplete="off" type="text" name="name"--}}
                            <input id="testInput" >
{{--                            <input  type="text" id="testInput" value=""/>--}}
                        </div>


                        <div class="row">
                            <div class="mt-3 ml-3">
                                <input type="submit" value="Add Participant" class="create-only btn btn-primary btn-block" id="addPar" >
                                <input type="submit" name="commit" value="Update Room" class="update-only btn btn-primary btn-block" data-disable-with="Update Room" style="display:none !important">
                            </div>
                        </div>

{{--                        {!! Form::close() !!}--}}
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <p class="text-primary"><strong> Info ! </strong> Participants need to singup if he's not member of this site. Invitational mail will be sent to his email </p>

                </div>
            </div>

        </div>
    </div>





@stop

@section('script')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>


        (function($) {



            $('#addPar').on('click',function () {

                let emails = [];
                $('#ul .tag span').each(function(i)
                {
                    emails.push($(this).text())

                });
                $.ajax({
                    type:'POST',
                    url:'{{route("roomAttendees")}}',
                    datatype:'json',
                    data:{
                        emails:emails,
                        "_token":"{{csrf_token()}}"
                    },success:function (data) {


                    },

                });


            })
            $.fn.tags = function(opts) {
                var selector = this.selector;
                function update($original) {
                    var all = [];
                    var list = $original.closest(".tags-wrapper").find("li.tag span").each(function() {
                        all.push($(this).text());
                    });
                    all = all.join(",");
                    $original.val(all);
                }

                return this.each(function() {
                    var self = this,
                        $self = $(this),
                        $wrapper = $("<div class='tags-wrapper'><ul id='ul'></ul></div>");
                    tags = $self.val(),
                        tagsArray = tags.split(","),
                        $ul = $wrapper.find("ul");



                    // make sure have opts
                    if(!opts) opts = {};
                    opts.maxSize = 50;

                    // add tags to start
                    tagsArray.forEach(function(tag) {
                        if(tag) {
                            $ul.append("<li class='tag' name='email[]'><span>"+tag+"</span><a href='#'>x</a></li>");
                        }
                    });


                    // get classes on this element
                    if(opts.classList) $wrapper.addClass(opts.classList);

                    // add input
                    $ul.append("<li class='tags-input'><input type='text' class='tags-secret form-control text-center border' placeholder='Enter Emails'/></li>");
                    // set to dom
                    $self.after($wrapper);
                    // add the old element
                    $wrapper.append($self);

                    // size the text
                    var $input = $ul.find("input"),
                        size = parseInt($input.css("font-size"))-4;

                    // delete a tag
                    $wrapper.on("click","li.tag a",function(e) {
                        e.preventDefault();
                        $(this).closest("li").remove();
                        $self.trigger("tagRemove",$(this).closest("li").find("span").text());
                        update($self);
                    });

                    // backspace needs to check before keyup
                    $wrapper.on("keydown","li input",function(e) {
                        // backspace
                        if(e.keyCode == 8 && !$input.val()) {
                            var $li = $ul.find("li.tag:last").remove();
                            update($self);
                            $self.trigger("tagRemove",$li.find("span").text());
                        }
                        // prevent for tab
                        if(e.keyCode == 9) {
                            e.preventDefault();
                        }

                    });

                    // as we type
                    $wrapper.on("keyup","li input",function(e) {
                        e.preventDefault();
                        $ul = $wrapper.find("ul");
                        var $next = $input.next(),
                            usingAutoFill = $next.hasClass("autofill-bg"),
                            $inputLi = $ul.find("li.tags-input");

                        // regular size adjust
                        $input.width($input.val().length * (size) );

                        // if combined with autofill, check the bg for size
                        if(usingAutoFill) {
                            $next.width($next.val().length * (size) );
                            $input.width($next.val().length * (size) );
                            // make sure autofill doesn't get too big
                            if($next.width() < opts.maxSize) $next.width(opts.maxSize);
                            var list = $next.data().data;
                        }

                        // make sure we don't get too high
                        if($input.width() < opts.maxSize) $input.width(opts.maxSize);

                        // tab, comma, enter
                        if(!!~[9,188,13].indexOf(e.keyCode)) {
                            var val = $input.val().replace(",","");
                            var otherCheck = true;

                            // requring a tag to be in autofill
                            if(opts.requireData && usingAutoFill) {
                                if(!~list.indexOf(val)) {
                                    otherCheck = false;
                                    $input.val("");
                                }
                            }

                            // unique
                            if(opts.unique) {
                                // found a match already there
                                if(!!~$self.val().split(",").indexOf(val)) {
                                    otherCheck = false;
                                    $input.val("");
                                    $next.val("");
                                }
                            }

                            // max tags
                            if(opts.maxTags) {
                                if($self.val().split(",").length == opts.maxTags) {
                                    otherCheck = false;
                                    $input.val("");
                                    $next.val("");
                                }
                            }

                            // if we have a value, and other checks pass, add the tag
                            if(val && otherCheck) {
                                // place the new tag
                                $inputLi.before("<li class='tag'><span>"+val+"</span><a href='#'>x</a></li>");
                                // clear the values
                                $input.val("");
                                if(usingAutoFill) $next.val("");
                                update($self);
                                $self.trigger("tagAdd",val);
                            }
                        }

                    });

                });
            }
            $("#testInput").tags({
                unique: true,
                maxTags: 100
            });
        })(jQuery);

    </script>
@stop