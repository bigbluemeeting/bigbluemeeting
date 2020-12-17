<template>
    <div id="userList">


                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
<!--                            <th>Roles</th>-->
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>


                        <tr v-for="user in users.data">
                            <td>{{user.id}}</td>
                            <td>{{user.name}}</td>
                            <td>{{user.email}}</td>
<!--                            <td>-->


<!--                                <span class="badge badge-danger" v-for="role in getRole(user.id)"  >-->
<!--                                    {{role}}-->
<!--                                </span>-->

<!--                            </td>-->
                            <td>
                                <a href="" class="btn btn-sm btn-info">Edit</a>

                            </td>
                        </tr>
                        </tbody>
                    </table>

                </div>


        </div>

</template>

<script>
    export default {
        name: "usersList",
        props: {
            userList: { type: String},
            userRole:{type:String}


        },
        data(){
            return{

                users :{},
                roles:{},
            }
        },
        created() {
            axios.get(this.userList)
                .then((response) =>this.users=response.data.users)
                .catch((error) =>console.log(error));


        },
        methods:{
            getRole(id){
                var roleUrl = this.userRole.replace(':id',id);

                return axios.get(roleUrl)
                    .then((response) => {
                        console.log('2. server response:' + response.data.roles)
                        this.roles = response.data.roles;
                    });




            }
        }
    }
</script>

<style scoped>

</style>