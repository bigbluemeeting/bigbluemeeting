<template>
    <div id="rooms-list-app">
        <div class="row">

            <div class="col-sm-6 col-sm-offset-5">
                <pagination :data="rooms" @pagination-change-page="getResults"></pagination>
            </div>
        </div>
        <div v-if="rooms.total > 0" class="table-responsive">
            <table class="table  table-hover">
                <thead>
                <tr>
                    <th>Room Name</th>
                    <th>Create Date</th>
                    <th>Invite Participants</th>
                    <th>Details</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                <tr v-for="room in rooms.data" v-bind:key="room.id">
                    <td>{{room.name}}</td>
                    <td>{{ moment(room.created_at) }}</td>

                    <td contenteditable="true">{{url+'/'+room.url}}</td>
                    <td><a :href="detailsUrl(room.url)" class="btn btn-sm btn-info">Show Details</a></td>
                    <td>
                        <a :href="generateJoinUrl(room.url)" target="_blank" class="btn btn-sm  btn-primary-outline">Start</a>
                        |
                        <span :data-task="room.id"  @click="getSingleRoomRecord(room.id)"  class="btn btn-sm btn-info-outline btn-manage"><i class="fa fa-edit"></i> Edit</span>
                        |
                        <span @click="deleteUrl(room.id)" class="btn btn-sm btn-danger-outline btnDeleteConfirm"><i class="fa fa-trash"></i> Delete</span>
                    </td>

                </tr>

                </tbody>
            </table>
        </div>
        <div v-if="rooms.total === 0" class="card bg-light">
            <div class="card-body" style="background: rgb(255, 248, 160) none repeat scroll 0% 0%">

                <div class="col-md-12">
                    <p class="text-danger m-0">We're sorry, you don't have any rooms.</p>
                </div>
                <div class="col-md-12">
                    <p class="text-danger pt-1">To create a new room,press the "Room" button.</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 col-sm-offset-5">
                <pagination :data="rooms" @pagination-change-page="getResults"></pagination>
            </div>
        </div>
        <add-room :route="createRoomRoute" @roomAdded="upgradeRoomList"></add-room>
        <edit-room ref="editRoomComponent" :updateRoute="updateRoomRoute" @roomAdded="upgradeRoomList"></edit-room>
        <delete-room-modal :delete-room-route="delete_url" @roomAdded="upgradeRoomList"></delete-room-modal>
    </div>

</template>

<script>

    Vue.component('pagination', require('laravel-vue-pagination'));
    Vue.component('editRoom',require('./editRoomModal.vue'));
    Vue.component('addRoom',require('./addRoomModal.vue'));
    Vue.component('DeleteRoomModal',require('./deleteRoomModal.vue'));

    export default {

        props: {

            singleRoomRoute:{ type: String},
            createRoomRoute:{ type: String},
            roomRoute:{ type: String},
            roomDetails:{ type: String},
            joinUrl :{ type: String},
            updateRoomRoute:{type:String},
            deleteRoute:{type:String}


        },
        data()
        {
            return {
                rooms:{},
                url :window.location,
                rec :'',
                delete_url:'',

            }
        },

        created(){
            axios.get(this.roomRoute)
                .then((response) => {
                    this.rooms = response.data.rooms;

                })
                .catch((error) =>console.log(error));

        },
        methods:{
            detailsUrl (url)
            {
                var Url =this.roomDetails.replace(':id',url);
               return Url;

            },
            generateJoinUrl(url)
            {
                var Url =this.joinUrl.replace(':id',url);
                return Url;

            },
            moment: function (date) {
                return moment(date).format('MMM D,YYYY h:mm A');
            },
            getResults(page = 1) {
                axios.get(this.roomRoute+'?page=' + page)

                    .then(response => this.rooms = response.data.rooms ).catch(error=>console.log(error));
            },
            upgradeRoomList(rooms)
            {
                this.rooms = rooms.data.rooms;

                if(rooms.data.autoJoin)
                {
                    window.location = rooms.data.url;
                }

            },
            getSingleRoomRecord(id)
            {
                url = this.singleRoomRoute.replace(':id',id);
                axios.get(url)
                    .then((response) =>{

                        this.rec=response.data.result;
                        this.$refs.editRoomComponent.showModal(this.rec);

                    })
                    .catch((error) =>console.log(error));
            },
            deleteUrl(id)
            {
                this.delete_url = this.deleteRoute.replace(':id',id);
                $('#DeleteModal').modal('show');
            }

        }
    }
</script>

<style scoped>

</style>
