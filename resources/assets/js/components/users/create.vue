<template>
    <div id="users">
        <div class="card card-block sameheight-item">
            <form action="" @submit="formSubmit">
            <div class="form-group}">
                <label for="name" class="col-sm-4 control-label">Name *</label>

                <div class="col-sm-12">
                    <input type="text" name="name" v-model="fields.name" id="name" class="form-control" placeholder="Enter Name of User">
                    <ul v-if="error.name" class="list-unstyled mt-2 errorUl">
                        <li v-for="err in error.name" class="text-danger">{{err}}</li>
                    </ul>
                </div>
            </div>

            <div class="form-group">
                <label for="username" class="col-sm-4 control-label">Username *</label>
                <div class="col-sm-12">
                    <input type="text" id="username" name="username" v-model="fields.username"class="form-control" placeholder="Enter username of User">
                    <ul v-if="error.username" class="list-unstyled mt-2 errorUl">
                        <li v-for="err in error.username" class="text-danger">{{err}}</li>
                    </ul>
                </div>
            </div>

            <div class="form-group">
                <label for="email" class="col-sm-4 control-label">Email *</label>
                <div class="col-sm-12">
                    <input type="email" class="form-control" name="email" v-model="fields.email"  placeholder="Enter Email of User" id="email">
                    <ul v-if="error.email" class="list-unstyled mt-2 errorUl">
                        <li v-for="err in error.email" class="text-danger">{{err}}</li>
                    </ul>
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="col-sm-4 control-label">Password *</label>
                <div class="col-sm-12">
                    <input type="password" name="password" v-model="fields.password" class="form-control" placeholder="Enter Password of User" id="password">
                    <ul v-if="error.password" class="list-unstyled mt-2 errorUl">
                        <li v-for="err in error.password" class="text-danger">{{err}}</li>
                    </ul>
                </div>
            </div>
                <div class="form-group">
                    <label for="roles"  class="col-sm-4 control-label">Roles *</label>
                   <div class="col-sm-12">
                        <select v-model="fields.roles"  name="roles[]" id="roles" class="form-control select2" multiple required>
                            <option v-bind:value="role" v-for="role in roles">{{ role}}</option>
                        </select>
                       <ul v-if="error.roles" class="list-unstyled mt-2 errorUl">
                           <li v-for="err in error.roles" class="text-danger">{{err}}</li>
                       </ul>
                    </div>
                </div>



            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-sm-12 text-center">
                    <input v-if="newUser" type="submit" class="btn btn-primary btn-large" value="Save" >
                    <input v-else type="submit" class="btn btn-primary btn-large" value="Update" >

                </div>
            </div>
            </form>



        </div>

    </div>

</template>

<script>
    import {eventBus} from "../../app.js";

    export default {
        name: "create",
        props: {
            formRoute: { type: String},
            userRoles:{type:String},
            updateUser:{type:String}
        },
        data(){
            return {

                roles :[],
                usersRoles:[],
                fields:{
                    roles:["administrator"],
                    name:null,
                    email:null,
                    username:null,
                    password:null
                },
                error:[],
                newUser:true,
                userId:null


            }
        },
        methods:{
            formSubmit: function(e) {

                e.preventDefault();


                if (this.newUser)
                {
                    axios.post(this.formRoute,
                        this.fields
                    ).then(response=> {
                        this.afterSubmit(response)

                    })
                        .catch(error=> {

                            this.error =error.response.data.errors

                        });
                }else{

                    this.fields['_method'] = 'PUT';
                    var editUrl = this.updateUser.replace(':id',this.userId);
                    axios.post(editUrl, this.fields).then(response=> {
                        this.afterSubmit(response)

                    }).catch(error => this.error = error.response.data.errors);

                }


            },
            afterSubmit(response){
                eventBus.newUser(response.data.users);
                this.fields = {};
                this.fields.roles=['administrator']
                this.newUser = true;
                this.error={}

            }

        },
        created() {

            this.roles=JSON.parse(this.userRoles)
            eventBus.$on("userEdit", (userData) => {

                this.fields.name=userData.name
                this.fields.username=userData.username
                this.fields.email=userData.email
                this.fields.roles=userData.roles
                this.userId=userData.id
                this.newUser=false

            });


        }

    }
</script>

<style scoped>

</style>