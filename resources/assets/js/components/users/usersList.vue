<template>
    <div id="userList">

        <div class="row">

            <div class="col-sm-6 col-sm-offset-5">
                <pagination :data="users" @pagination-change-page="getResults"></pagination>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table  table-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>


                <tr v-for="user in users.data">
                    <td>{{user.id}}</td>
                    <td>{{user.name}}</td>
                    <td>{{user.email}}</td>
                    <td>
                        <span class="badge badge-danger col-md-12"  v-for="role in user.roles" >
                            {{role.name}}<br/>
                        </span>
                    <td>
                        <span  @click="getSingleUserRecord(user.id)" class="btn btn-sm btn-info">Edit</span>
                        |
                        <span @click="deleteUser(user.id)"  class="btn btn-sm btn-danger">Delete</span>

                    </td>
                </tr>
                </tbody>
            </table>

        </div>
        <div class="row">

            <div class="col-sm-6 col-sm-offset-5">
                <pagination :data="users" @pagination-change-page="getResults"></pagination>
            </div>
        </div>
        <delete-user-modal :delete-user-route="userDeleteUrl"></delete-user-modal>
<!--        <delete-user-modal></delete-user-modal>-->
<!--        < :delete-room-route="delete_url" @roomAdded="upgradeRoomList"></>-->



    </div>

</template>

<script>
    import {eventBus} from "../../app.js";

    Vue.component('pagination', require('laravel-vue-pagination'));
    Vue.component('DeleteUserModal',require('./DeleteUserModal.vue'));

    export default {
        name: "usersList",
        props: {
            userList: { type: String},
            userEdit: { type: String},
            userDelete:{ type: String},


        },
        data(){
            return{

                users :{},
                roles:{},
                userDeleteUrl:null
            }
        },
        created() {
            axios.get(this.userList)
                .then((response) =>this.users=response.data.users)
                .catch((error) =>console.log(error));

            eventBus.$on("userAdded", (userData) => {

                this.users=userData;

            });
            console.log(this.userDelete);


        },
        methods:{
            getSingleUserRecord(id)
            {
                var singleUrl = this.userEdit.replace(':id',id)
                var roles=[];
                axios.get(singleUrl)
                    .then((response) =>{
                        var user = response.data.user

                        $.each(user.roles,function(item,val){
                            roles.push(val.name);
                        });

                        var userData = {
                            'id':user.id,
                            'name':user.name,
                            'username':user.username,
                            'email':user.email,
                            'roles':roles

                        };
                        eventBus.editUser(userData);

                    }).catch((error) =>console.log(error));
            },
            getResults(page = 1) {
                axios.get(this.userList+'?page=' + page)
                    .then(response => this.users = response.data.users).catch(error=>console.log(error));
            },
            deleteUser(id)
            {
                this.userDeleteUrl = this.userDelete.replace(':id',id);

                $('#delete-user-modal').modal('show');

            }
        }
    }
</script>

<style scoped>

</style>
