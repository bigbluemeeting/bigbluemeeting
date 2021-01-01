<template>
    <div id="invite-participant-app">
        <div class="table-responsive" v-if="showData">
            <table class="table table-striped" >
                <tbody>
                <tr>
                    <td>Meeting Name</td>
                    <td>{{meetingInformation.name}}</td>

                </tr>
                <tr>
                    <td>Start Time (Local)</td>
                    <td>{{moment(meetingInformation.start_date)}}</td>

                </tr>
                <tr>
                    <td>End Time (Local)</td>
                    <td>{{moment(meetingInformation.end_date)}}</td>
                </tr>
                <tr>
                    <td>No. of Participants</td>
                    <td>{{meetingInformation.maximum_people}}</td>

                </tr>

                <tr>
                    <td>Recording</td>
                    <td>{{meetingInformation.meeting_record==='1'?'YES':'NO'}}</td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td>{{meetingInformation.meeting_description}}</td>
                </tr>


                </tbody>
            </table>
            <hr >
            <div class="container mt-3" >
                <h5><i class="fa fa-user"></i>&nbsp;&nbsp;Meeting Participants</h5>
            </div>

            <div class="input-group" v-if="!pastMeeting" >
                <div class="input-group-prepend">
                    <div class="col-md-11 mt-2">
                        <span class="create-only btn btn-info btn-block input-group-text" data-toggle="modal" data-target="#myModal" id="createRoom">
                            <i class="fa fa-plus-circle text-center text-white pr-3">&nbsp; Invite Participant <i class="fa fa-caret-down ml-1"></i></i>
                        </span>

                    </div>
                </div>
            </div>

        </div>


        <div class="row">
            <div class="col-sm-6 col-sm-offset-5 ml-3 mt-4">
                <pagination :data="attendees" @pagination-change-page="getResults"></pagination>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card bg-white m-0" v-if="attendees.total > 0">
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

                                <tr v-for="attendee in attendees.data" v-bind:key="attendee.id">
                                    <td>{{attendee.email}}</td>
                                    <td>{{moment(attendee.created_at)}}</td>
                                    <td  class="text-center"><span @click="removeAttendee(attendee.id)" class="btn btn-danger-outline"><i class="fa fa-remove"></i> Remove</span></td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <div class="card bg-light" v-else>
                    <div class="card-body" style="background: #fff8a0;">

                        <div class="col-md-12" >
                            <p class="text-danger m-0">There are no participants invited to this meeting.</p>

                            <p v-if="!pastMeeting" class="text-danger pt-1">To participants to this meeting,click the blue button on the top left.</p>

                        </div>
                    </div>
                </div>

            </div>

        </div>
        <div class="row">
            <div class="col-sm-6 col-sm-offset-5 ml-3">
                <pagination :data="attendees" @pagination-change-page="getResults"></pagination>
            </div>
        </div>
<!--         Add Participant Modal   -->
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
                                <div v-if="error" class="alert alert-danger errorClass">
                                    {{error}}
                                    <i class="fa fa-times float-right" @click="closeError" id="cross-icon"></i>
<!--                                    <i class></i>-->
                                </div>
                                <div class="input-icon mt-12 ml-12">
                                    <input v-model="participantEmail">
                                </div>
                                <div class="row">
                                    <div class="mt-3 ml-3">
                                        <input type="button" @click="submitParticipant" value="Add Participants" class="create-only btn btn-primary btn-block" id="addPar" >
                                        <input type="submit" name="commit" value="Update Room" class="update-only btn btn-primary btn-block" data-disable-with="Update Room" style="display:none !important">
                                    </div>
                                </div>
                            </div>


                    </div>
                    <input type="hidden" id="room" v-model="participant.room">
                    <div class="modal-footer bg-light">
                        <p class="text-primary"><strong> Info ! </strong> Participants need to singup if they are not members of this site. Invitational mail will be sent to their email.</p>

                    </div>
                </div>

            </div>
        </div>
    </div>


</template>

<script>
    Vue.component('pagination', require('laravel-vue-pagination'));

    export default {
        name: "addParticipants",
        props:{
            meetingUrl :{
                type:String,
                required:true
            },
            meetingAttendee:{
                type:String,
                required:true
            },
            participantRoute :{
                type:String,
                required:true
            },
            meetingAttendeeRemove:{
                type:String,
                required:true
            },


        },
        data()
        {
          return {
              meetingInformation:{},
              participantEmail: "",
              attendees :{},
              files :{},
              pastMeeting:false,
              showData :false,
              error:null,
              participant:{
                  room:null,
                  emails:[]
              },
              attendeeData:{
                  id:null,
                  url:null
              }

          }
        },
        created() {
            var route= this.participantRoute.replace(':url',this.meetingUrl)

            axios.get(route)
                .then(response=>{
                    this.meetingInformation = response.data.meeting
                    this.attendees=response.data.attendees
                    this.pastMeeting = response.data.pastMeeting
                    this.showData=true
                    this.participant.room=this.meetingInformation.url


                })
                .catch(error=>console.log(error))

        },
        methods:{
            moment:function (date) {
                return moment(date).format('MMM D,YYYY h:mm A');

            },
            getResults(page = 1) {
                var route= this.participantRoute.replace(':url',this.meetingUrl)

                axios.get(route+'?page=' + page)

                    .then(response =>this.attendees=response.data.attendees).catch(error=>console.log(error));
            },
            submitParticipant()
            {
                this.participant.emails.push(this.participantEmail)
                axios.post(this.meetingAttendee, this.participant
                ).then(response=> {
                    this.participantEmail="";
                    this.attendees=response.data.attendees
                }).catch(error=> {
                    this.error=error.response.data.error
                       // console.log(error.response.data)
                    });
            },
            closeError()
            {
                this.error = null;

            },
            removeAttendee(id)
            {
                this.attendeeData.id=id;
                this.attendeeData.url=this.meetingUrl;
                axios.post(this.meetingAttendeeRemove, this.attendeeData
                ).then(response=> {
                    this.attendees=response.data.attendees
                })
                    .catch(error=> {
                        console.log( error.response.data.errors);
                    });
            }
        }
    }
</script>

<style scoped>
#cross-icon{
    cursor: pointer;
}
</style>
