<template>
    <div id="app">

        <div v-if="rooms.total > 0" class="table-responsive">

            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>Room Name</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>



                <tr v-for="room in rooms.data">
                    <td>{{ room.name }}</td>
                    <td>{{moment(room.start_date)}}</td>
                    <td>{{moment(room.end_date)}}</td>
                    <td><a href='javascript:void(0)' :data-id="room.url" class="btn btn-sm btn-primary InvitedMeetingAttendeeJoin form-control"  id="">Join</a></td>


                </tr>

                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-sm-6 col-sm-offset-5">
                <pagination :data="rooms" @pagination-change-page="getResults"></pagination>
            </div>
        </div>
    </div>
</template>

<script>
    Vue.component('pagination', require('laravel-vue-pagination'));
    export default {
        // name: "invitedMeetingsList",
        data(){
            return {
                rooms:{},
            }
        },
        props: {
            roomRoute:{ type: String},
        },
        created(){
            // console.log(this.roomRoute)
            axios.get(this.roomRoute)
                .then((response) => {
                    console.log(response.data)
                    this.rooms = response.data;

                })
                .catch((error) =>console.log(error));

        },
        methods:{
            moment: function (date) {
                return moment(date).format('MMM D,YYYY h:mm A');
            },
            getResults(page = 1) {
                axios.get(this.roomRoute+'?page=' + page)

                    .then(response => this.rooms = response.data).catch(error=>console.log(error));
            },
        }
    }
</script>

<style scoped>

</style>