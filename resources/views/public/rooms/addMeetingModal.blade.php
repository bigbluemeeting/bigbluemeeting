
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog  modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <div class="card-body p-sm-6">
                    <div class="card-title">
                        <h3 class="text-center">Create New Meeting</h3>
                        <h3 class="update-only" style="display:none !important">Meeting Settings</h3>
                    </div>
                    {!! Form::open(['method' => 'POST', 'route' => ['meetings.store'], 'class'=>'form-horizontal addForm']) !!}

                    <div class="input-icon mb-2">
                            <span class="input-icons">
                                <i class="fa fa-desktop icon mt-1 ml-2"></i>
                            </span>
                        <input id="create-meeting-name" class="form-control text-center" value="" placeholder="Enter a Meeting name..." autocomplete="off" type="text" name="name">


                    </div>

                    <div class="row mt-2">
                        <div class="col-md-12">
                                <span class="input-icons">
                                <i class="fa fa-users icon mt-1 ml-2"></i>
                                </span>
                            <input id="create-meeting-name" class="form-control text-center" value="" placeholder="Enter a Maximum People..." autocomplete="off" type="text" name="maximum_people">
                        </div>
                    </div>
                    <label for="start_date" class="mt-2" >Meeting Start on</label>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="input-group">
                                <input type="text"  name="start_date" id="" placeholder="Enter Start Date" class="form-control text-center picker">
                                <div class="input-group-prepend">
                                        <span id="toggle" class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                </div>
                            </div>
                        </div>
                        <p class="mt-2 col-md-1">at</p>
                        <div class="col-sm-5 clockpicker1">
                            <div class="input-group">
                                <input type="text" name="startTime" class="form-control" id="startTime">
                                <div class="input-group-append">
                                            <span type="button" id="toggle3" class="input-group-text">
                                                <i class="fa fa-clock-o"></i>
                                            </span>
                                </div>
                            </div>
                        </div>

                    </div>
                    <label for="end_date">Meeting End on</label>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="input-group">
                                <input type="text"  name="end_date" id="" placeholder="Enter End Date" class="form-control text-center picker2">
                                <div class="input-group-prepend">
                                        <span type="button" id="toggle2" class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                </div>
                            </div>
                        </div>
                        <p class="mt-2  col-md-1">at</p>
                        <div class="col-sm-5 clockpicker2">
                            <div class="input-group">
                                <input type="text" name="endTime" class="form-control" id="endTime" >
                                <div class="input-group-append">
                                            <span type="button" id="toggle3" class="input-group-text">
                                                <i class="fa fa-clock-o"></i>
                                            </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <textarea class="form-control" name="meeting_description"  placeholder="A description of the invite to be send along with the e-mail invite" id="" cols="40" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-12">

                            <textarea class="form-control" name="welcome_message"  placeholder="A welcome message shown in the chat meeting" id="" cols="40" rows="1.5"></textarea>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label>Record This Meeting</label>
                            <select name="meeting_record"  class="form-control">
                                <option value="0">No,don't record it.</option>
                                <option value="1">Record it.</option>
                            </select>
                        </div>

                    </div>

                    <div class="row">
                        <div class="mt-3 ml-3">
                                <span class="create-only btn btn-info btn-block input-group-text advanceSettings" data-toggle="modal" >
                                    Advanced Settings
                                    <i class="fa fa-chevron-circle-down text-center text-dark pl-3 mt-1"></i>
                                </span>

                        </div>
                    </div>


                    <div class="container border border-light rounded mt-3 advancedOptions" style="display: none;" >
                        <div class="row">
                            <div class="col-sm-8">
                                <label for="meeting_mute_on_join" class="custom-switch pl-0 mt-3 mb-3 w-100 text-sm-left">
                                    Mute users when they join
                                </label>
                            </div>
                            <div class="col-sm-3 mt-3">
                                <input class="custom-switch-input" data-default="false" type="checkbox" value="1" name="mute_on_join" id="meeting_mute_on_join" autocomplete="off">
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="mt-3 ml-3">
                            <input type="submit" value="Schedule Meeting" class="create-only btn btn-info btn-block" data-disable-with="Create Meeting">
                            <input type="submit" name="commit" value="Update Meeting" class="update-only btn btn-primary btn-block" data-disable-with="Update Meeting" style="display:none !important">
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
            <div class="modal-footer">
                <h6 style="margin-right: 60px;">You will be free to delete this meeting at any time.</h6>
                <p class="update-only" style="display:none !important">Adjustment to your meeting can be done at anytime.</p>
            </div>
        </div>

    </div>
</div>
