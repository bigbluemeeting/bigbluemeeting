<template>
    <div id="upcoming-meetings-app">
        <div v-if="meetings.total > 0" class="table-responsive">
            <table class="table  table-hover">
                <thead>
                <tr>
                    <th>Meeting Name</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Recorded</th>
                    <th>Details</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="meeting in meetings.data" v-bind:key="meeting.id">
                    <td><a :href="links()+ '/' + meeting.url">{{ meeting.name }}</a></td>
                    <td>{{ moment(meeting.start_date) }}</td>
                    <td>{{ moment(meeting.end_date) }}</td>
                    <td>{{ meeting.meeting_record == 1 ?'Yes':'No'}}</td>
                    <td>
                        <a :href="links()+'/details/'+ meeting.url" class="btn btn-sm  btn-info">Show Details</a>
                    </td>
                    <td>
                        <span  :data-task="+meeting.id" @click="getSingleRecord(meeting.id)" class="btn btn-sm btn-info-outline btn-manage"><i class="fa fa-edit" ></i> Edit</span>
                        |
                        <span  :data-item ="+meeting.id"  @click="deleteRecordSetId(meeting.id)" class="btn btn-sm btn-danger-outline btnDeleteConfirm"><i class="fa fa-trash"></i> Delete</span>

                    </td>
                </tr>

                </tbody>
            </table>

        </div>
        <div v-if="meetings.total === 0" class="card" style="background: #fff8a0;">
            <div class="card-body">
                <div class="col-md-12">
                    <p class="text-danger m-0">We're sorry, you don't have any in-progress meetings or upcoming meetings.</p>
                </div>
                <div class="col-md-12">
                    <p class="text-danger pt-1">To create a new meeting, press the "Meeting" button.</p>
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-sm-6 col-sm-offset-5">
                <pagination :data="meetings" @pagination-change-page="getResults"></pagination>
            </div>
        </div>
        <editMeeting ref="editMeetingComponent" :record="rec" :route="updateMeetingRoute" @updateMeeting="meetingAdded"></editMeeting>

        <addMeeting @addMeeting="meetingAdded" :formRoute="meetingRoute"></addMeeting>


    </div>


</template>

<script>

    import {eventBus} from "../../app.js";

    Vue.component('addMeeting',require('./addMeetingModal.vue'));
    Vue.component('editMeeting',require('./editMeetingModal'));
    Vue.component('pagination', require('laravel-vue-pagination'));

    export default {
        props: {
            meetingRoute: { type: String},
            getMeetingRoute:{ type: String},
            singleMeetingRoute :{ type: String},
            updateMeetingRoute :{ type: String},
            deleteMeetingRoute :{ type: String},

        },
        data(){
            return {
                meetings:{},
                url : window.location,
                rec :'',
                deleteRecord:'',

            }
        },
        created(){
            axios.get(this.getMeetingRoute)
                .then((response) => this.meetings = response.data)
                .catch((error) =>console.log(error));

            eventBus.$on("upcomingMeeting", (myData) => {
                this.meetings = myData.data

            });
        },
        methods:
        {
            meetingAdded(meetings)
            {

                this.meetings = meetings.data
            },
            links: function() {
                return this.url;
            },
            moment: function (date) {
                return moment(date).format('MMM D,YYYY h:mm A');
            },
            getResults(page = 1) {
                axios.get(this.getMeetingRoute+'?page=' + page)
                    .then(response => this.meetings = response.data).catch(error=>console.log(error));
            },
            getSingleRecord(id)
            {

               url = this.singleMeetingRoute.replace(':id',id);
                axios.get(url)
                    .then((response) =>{
                        this.rec=response.data.result;
                        this.$refs.editMeetingComponent.showModal(this.rec);

                    })
                    .catch((error) =>console.log(error));
            },
            deleteRecordSetId(id)
            {

                var deleteObject = {
                    'id':id,
                    'upcoming' : true
                };
                eventBus.deleteID(deleteObject);
            },
        },
    }
</script>
