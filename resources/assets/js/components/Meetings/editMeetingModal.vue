<template>
    <div id="editModal" ref="vuemodal" class="modal fade" role="dialog">
        <div class="modal-dialog  modal-dialog-centered modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card-body p-sm-6">
                        <div class="card-title">
                            <h3 class="text-center">Edit Your Meeting</h3>
                        </div>

                        <form @submit="updateMeeting" class="form-horizontal manageForm">
                        <div class="input-icon mb-2">
                            <span class="input-icons">
                                <i class="fa fa-desktop icon mt-1 ml-2"></i>
                            </span>
                            <input id="edit-room-name" class="form-control text-center"  placeholder="Enter a Room name..." autocomplete="off" type="text" name="name" v-model="record.name" >
                            <ul v-if="error.name" class="list-unstyled mt-2 errorUl">
                                <li v-for="err in error.name" class="text-danger">{{err}}</li>
                            </ul>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12">
                                <span class="input-icons">
                                <i class="fa fa-users icon mt-1 ml-2"></i>
                                </span>
                                <input id="edit-max-people" class="form-control text-center" placeholder="Enter a Maximum People..." autocomplete="off" type="text" name="maximum_people" v-model="record.maximum_people">
                                <ul v-if="error.maximum_people" class="list-unstyled mt-2 errorUl">
                                    <li v-for="err in error.maximum_people" class="text-danger">{{err}}</li>
                                </ul>
                            </div>
                        </div>
                        <label for="start_date" class="mt-2" >Meeting Start on</label>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="input-group">

                                    <input type="text" id="start_date"  ref="startDate"  placeholder="Enter Start Date" class="form-control text-center picker editPicker">

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
                                    <div class="input-group date" id="datetimepicker4" data-target-input="nearest">
                                        <input type="text" id="start_time"  ref="startTime"  name="startTime"  class="form-control datetimepicker-input" data-target="#datetimepicker4" />
                                        <div class="input-group-append" data-target="#datetimepicker4" data-toggle="datetimepicker">
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
                                    <input type="text"  ref="endDate" id="end_date" placeholder="Enter End Date" class="form-control text-center picker2 editPicker2">
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
                                    <div class="input-group date" id="datetimepicker3" data-target-input="nearest">
                                        <input type="text" id="end_time" ref="endTime" name="endTime"  class="form-control datetimepicker-input" data-target="#datetimepicker3"/>
                                        <div class="input-group-append" data-target="#datetimepicker3" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-clock-o"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <textarea class="form-control meeting_description" name="meeting_description"  placeholder="A description of the invite to be send along with the e-mail invite"  cols="40" rows="2" v-model="record.meeting_description"></textarea>
                                <ul v-if="error.meeting_description" class="list-unstyled mt-2 errorUl">
                                    <li v-for="err in error.meeting_description" class="text-danger">{{err}}</li>
                                </ul>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12">

                                <textarea class="form-control welcome_message" name="welcome_message" v-model="record.welcome_message" placeholder="A welcome message shown in the chat room" id="" cols="40" rows="1.5"></textarea>
                                <ul v-if="error.welcome_message" class="list-unstyled mt-2 errorUl">
                                    <li v-for="err in error.welcome_message" class="text-danger">{{err}}</li>
                                </ul>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label>Record This Meeting</label>
                                <select name="meeting_record"  class="form-control meeting_record" v-model="record.meeting_record">
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
                                        <input class="custom-switch-input mute_on_join" id="room_mute_on_join" data-default="false" type="checkbox" value="1" name="mute_on_join" autocomplete="off" v-model="record.mute_on_join">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mt-3 ml-3">
                                    <input type="submit" name="commit" value="Update Meeting" class="update-only btn btn-info btn-block" data-disable-with="Update Room" >
                                </div>
                            </div>
                        </form>
                        <span style="display:none" class="btn btn-danger" ref="closeButton" data-dismiss="modal">Close</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

                <script>
            export default {
                name: "editMeetingModal",
                props :["record","route"],
                data(){
                    return{
                        fields : {},
                        startDate:'',
                        error :[],
                        valuec:this.dateFormat(this.record.start_date)
            }
        },

                methods :{
                    dateFormat: function (date) {
                        return moment(date).format('YYYY-MM-DD');
                    },
                    timeFormat: function (date) {
                        return moment(date).format('h:mm A');
                    },
                    updateMeeting(e)
                    {
                        e.preventDefault();
                        url = this.route.replace(':id',this.record.id);
                        this.fields = this.record;
                        this.fields['start_date'] = this.$refs.startDate.value;
                        this.fields['startTime'] = this.$refs.startTime.value;
                        this.fields['end_date'] =this.$refs.endDate.value;
                        this.fields['endTime'] = this.$refs.endTime.value;
                        this.fields['_method'] = 'PUT';

                        axios.post(url, this.fields).then(data=> {
                            this.$emit('updateMeeting',data);
                            $('#editModal').modal('hide');
                        }).catch(error => this.error = error.response.data.errors);


                    },

                    showModal(record)
                    {


                        this.$refs.startDate.value = this.dateFormat(record.start_date);
                        this.$refs.startTime.value= this.timeFormat(record.start_date);
                        this.$refs.endDate.value = this.dateFormat(record.end_date);
                        this.$refs.endTime.value =this.timeFormat(record.end_date);



                        $('#editModal').modal('show');
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