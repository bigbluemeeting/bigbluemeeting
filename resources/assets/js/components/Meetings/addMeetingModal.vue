<template>

    <div id="myModal" ref="vuemodal" class="modal fade" role="dialog" >
        <div class="modal-dialog  modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card-body p-sm-6">
                        <div class="card-title">
                            <h3 class="text-center">Create New Meeting</h3>
                            <h3 class="update-only" style="display:none !important">Room Settings</h3>
                        </div>

                        <form  method="POST"  @submit="formSubmit" class="form-horizontal addForm" id="form">
<!--                            <input type="hidden" name="_token" :value="token" >-->
                            <div class="input-icon mb-2">
                            <span class="input-icons">
                                <i class="fa fa-desktop icon mt-1 ml-2"></i>
                            </span>
                            <input id="create-room-name" class="form-control text-center" value="" placeholder="Enter a Meeting name..." autocomplete="off" type="text" name="name" v-model="fields.name">
                            <ul v-if="error.name" class="list-unstyled mt-2 errorUl">
                                <li v-for="err in error.name" class="text-danger">{{err}}</li>
                            </ul>

                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12">
                                <span class="input-icons">
                                <i class="fa fa-users icon mt-1 ml-2"></i>
                                </span>
                                <input  class="form-control text-center" value="" placeholder="Enter a Maximum People..." autocomplete="off" type="text" name="maximum_people" v-model="fields.maximum_people">
                                <ul v-if="error.maximum_people" class="list-unstyled mt-2 errorUl">
                                    <li v-for="err in error.maximum_people" class="text-danger">{{err}}</li>
                                </ul>
                            </div>
                        </div>
                        <label for="start_date" class="mt-2" >Meeting Start on</label>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <input type="text"  ref='startDate' name="start_date"  id="start_date" placeholder="Enter Start Date" class="form-control text-center picker">
                                    <div class="input-group-prepend">
                                        <span id="toggle" class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-2 ml-3">at</p>


                            <div class="col-sm-5">
                                <div class="form-group">
                                    <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                                        <input  ref='startTime' type="text" name="startTime" class="form-control datetimepicker-input" data-target="#datetimepicker1" id="startTime"/>
                                        <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-clock-o"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <label for="end_date">Meeting End on</label>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <input type="text" ref="endDate"  name="end_date" id="end_date" placeholder="Enter End Date" class="form-control text-center picker2">
                                    <div class="input-group-prepend">
                                        <span type="button" id="toggle2" class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-2 ml-3">at</p>

                            <div class="col-sm-5">
                                <div class="form-group">
                                    <div class="input-group date"  id="datetimepicker2" data-target-input="nearest">
                                        <input type="text" ref="endTime" name="endTime"  class="form-control datetimepicker-input" data-target="#datetimepicker2" id="endTime"/>
                                        <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-clock-o"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <textarea class="form-control" name="meeting_description"  placeholder="A description of the invite to be send along with the e-mail invite" cols="40" rows="2" v-model="fields.meeting_description"></textarea>
                                <ul v-if="error.meeting_description" class="list-unstyled mt-2 errorUl">
                                    <li v-for="err in error.meeting_description" class="text-danger">{{err}}</li>
                                </ul>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12">

                                <textarea class="form-control" name="welcome_message"  placeholder="A welcome message shown in the chat room"  cols="40" rows="1.5" v-model="fields.welcome_message"></textarea>
                                <ul v-if="error.welcome_message" class="list-unstyled mt-2 errorUl">
                                    <li v-for="err in error.welcome_message" class="text-danger">{{err}}</li>
                                </ul>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label>Record This Meeting</label>
                                <select name="meeting_record"  class="form-control" id="meeting_record">
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
                                    <label for="room_mute_on_join" class="custom-switch pl-0 mt-3 mb-3 w-100 text-sm-left">
                                        Mute users when they join
                                    </label>
                                </div>
                                <div class="col-sm-3 mt-3">
                                    <input class="custom-switch-input" data-default="false" type="checkbox" value="1" name="mute_on_join" id="room_mute_on_join" v-model="fields.mute_on_join" autocomplete="off">
                                </div>
                            </div>


                        </div>


                        <div class="row">
                            <div class="mt-3 ml-3">
                                <input type="submit" value="Schedule Meeting" class="create-only btn btn-info btn-block" data-disable-with="Create Room">
                                <input type="submit" name="commit" value="Update Room" class="update-only btn btn-primary btn-block" data-disable-with="Update Room" style="display:none !important">
                            </div>
                        </div>

                        </form>
                    </div>
                </div>

            </div>

        </div>
    </div>
</template>

<script>

    export default {

        name: "addMeetingModal",
        props: {
            formRoute: { type: String},
            // token: { type: String}
        },

        data(){
            return {

                url : window.location,
                fields:{},
                error :[],

            }
        },
        methods:
            {

                formSubmit: function(e) {

                    e.preventDefault();


                    this.fields['start_date'] = this.$refs.startDate.value;
                    this.fields['startTime'] = this.$refs.startTime.value;
                    this.fields['end_date'] =this.$refs.endDate.value;
                    this.fields['endTime'] = this.$refs.endTime.value;
                    this.fields['meeting_record'] = document.getElementById('meeting_record').value;


                    axios.post(this.formRoute,
                       this.fields
                    ).then(data=> {


                        this.$emit('addMeeting',data);
                        this.fields = {};
                        $('#myModal').modal('hide');

                        })
                        .catch(error=> {
                            this.error = error.response.data.errors;

                        });

                },
                clearData()
                {
                    this.error=[]
                }

            },
        mounted() {

            $(this.$refs.vuemodal).on('focusout',this.clearData);
        },


    }
</script>

<style scoped>

</style>