<template>
    <div id="app">
        <div v-if="meetings.total > 0" class="table-responsive">
            <table class="table table-bordered table-hover">
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

                <tr v-for="meeting in meetings.data">

                    <td>{{ meeting.name }}</td>
                    <td>{{ moment(meeting.start_date) }}</td>
                    <td>{{ moment(meeting.end_date) }}</td>
                    <td>{{ meeting.meeting_record == 1 ?'Yes':'No'}}</td>
                    <td>
                        <a :href="links()+'/details/'+ meeting.url" class="btn btn-sm  btn-info">Show Details</a>
                    </td>
                    <td>
                        <span @click="deleteRecordSetId(meeting.id)" :data-item = "+meeting.id" class="btn btn-sm btn-danger-outline btnDeleteConfirm"><i class="fa fa-trash"></i> Delete</span>

                    </td>
                </tr>

                </tbody>
            </table>
        </div>
        <div v-else class="card bg-light">
            <div class="card-body">
                <div class="card">
                    <div class="card-body" id="warning-dev">
                        <div class="col-md-7">
                            <p class="text-danger m-0">We're sorry,you don't have any past meetings.</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-sm-6 col-sm-offset-5">
                <pagination :data="meetings" @pagination-change-page="getResults"></pagination>
            </div>
        </div>

    </div>

</template>

<script>

    import {eventBus} from "../../app.js";

    Vue.component('pagination', require('laravel-vue-pagination'));

    export default {
        data(){
            return {
                meetings:{},
                url : window.location,
                deleteRecord:'',
                showData : false

            }
        },
        props :{
            deleteMeetingRoute :{ type: String},
        },
        created(){
            axios.get(this.url+'/pastMeetings')
                .then((response) => {
                    this.meetings = response.data;
                })
                .catch((error) =>console.log(error));
            eventBus.$on("pastMeetings", (myData) => {
                this.meetings = myData.data

            });
        },
        methods:
            {
                links: function() {
                    return this.url;
                },
                moment: function (date) {
                    return moment(date).format('MMM D,YYYY h:mm A');
                },
                getResults(page = 1) {
                    axios.get(this.url+'/pastMeetings?page=' + page)
                        .then(response => this.meetings = response.data).catch(error=>console.log(error));
                },
                deleteRecordSetId(id)
                {
                    var deleteObject = {
                        'id':id,
                        'upcoming' : false
                    };

                    eventBus.deleteID(deleteObject);

                    // this.$refs.deleteMeetingComponent.showModal(id);

                },

            },


    }
</script>

<style scoped>

</style>