<template>
    <div id="mainDiv">
        <div id="editModal"  class="modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered">

                <!-- Modal content-->
                <div class="modal-content">

                    <div class="modal-body">
                        <div class="card-body p-sm-6">
                            <div class="card-title">
                                <h3 class="text-center">Create New Room</h3>
                                <h3 class="update-only" style="display:none !important">Room Settings</h3>
                            </div>
                            <!--                        {!! Form::open(['method' => 'POST', 'route' => ['rooms.store'], 'class'=>'form-horizontal addForm']) !!}-->
                            <form method="POST" @submit.prevent="roomSubmit">
                                <div class="input-icon mb-2">
                            <span class="input-icons">
                                <i class="fa fa-desktop icon ml-2"></i>
                            </span>
                                    <input id="create-room-name" class="form-control text-center" placeholder="Enter a Room name..." autocomplete="off" type="text" name="name" v-model="fields.name">
                                    <ul v-if="error.name" class="list-unstyled mt-2 errorUl">
                                        <li v-for="err in error.name" class="text-danger">{{err}}</li>
                                    </ul>
                                </div>

                                <div class="input-icon mb-2">
                             <span class="input-icons cursor-pointer" >
                                <i class="fa fa-lock icon ml-2 generate_access_code" @click="generateAccessCode"></i>
                            </span>
                                    <label  class="form-control text-sm-center create-room-access-code" for="room_access_code">{{accessCode}}</label>
                                    <input class="room_access_code" type="hidden" v-model="fields.access_code" name="access_code" id="room_access_code">
                                    <span  class="cursor-pointer" >
                                <i class="fa fa-trash-o float-right icon delete-icon" @click="deleteAccessCode"></i>
                            </span>
                                </div>
                                <div class="row">
                                    <div class="col-sm-8">
                                        <label for="room_mute_on_join" class="custom-switch pl-0 mt-3 mb-3 w-100 text-sm-left">
                                            <span class="custom-switch-description">Mute users when they join</span>
                                        </label>
                                    </div>
                                    <div class="col-sm-3 ml-4 mt-3">
                                        <input class="custom-switch-input" data-default="false" type="checkbox" value="1" name="mute_on_join" v-model="fields.mute_on_join" id="room_mute_on_join">
                                    </div>
                                </div>


                                <div class="row  mt-2">
                                    <div class="col-sm-8">
                                        <label for="room_anyone_can_start" class="custom-switch pl-0 mt-3 mb-3 w-100 text-left d-inline-block ">
                                            <span class="custom-switch-description">Allow any user to start this meeting</span>
                                        </label>
                                    </div>
                                    <div class="col-sm-3 ml-4 mt-3">
                                        <input class="custom-switch-input " data-default="false" type="checkbox" value="1" v-model="fields.anyone_can_start" name="anyone_can_start" id="room_anyone_can_start">
                                    </div>
                                </div>

                                <div class="row  mt-2">
                                    <div class="col-sm-8">
                                        <label for="room_all_join_moderator" class="custom-switch pl-0 mt-3 mb-3 w-100 text-left d-inline-block ">
                                            <span class="custom-switch-description">All users join as moderators</span>
                                        </label>
                                    </div>
                                    <div class="col-sm-3 ml-4 mt-4">
                                        <input class="custom-switch-input" data-default="false" type="checkbox" value="1" name="all_join_moderator" v-model="fields.all_join_moderator" id="room_all_join_moderator">
                                    </div>
                                </div>
                                <div class="row  mt-2">
                                    <div class="col-sm-8">
                                        <label  for="room_auto_join" id="auto-join-label" class="create-only custom-switch pl-0 mt-3 mb-3 w-100 text-left d-inline-block">
                                            <span class="custom-switch-description">Automatically join me into the room</span>
                                        </label>

                                    </div>
                                    <div class="col-sm-3 ml-4 mt-4">
                                        <input class="custom-switch-input" type="checkbox" value="1" name="auto_join" v-model="fields.auto_join" id="room_auto_join">
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <input type="submit" value="Update Room" class="create-only btn btn-primary btn-block" data-disable-with="Create Room">
                                    <input type="submit" name="commit" value="Update Room" class="update-only btn btn-primary btn-block" data-disable-with="Update Room" style="display:none !important">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <h6 style="margin-right: 60px;">You will be free to delete this room at any time.</h6>
<!--                        <p class="update-only" style="display:none !important">Adjustment to your room can be done at anytime.</p>-->
                    </div>
                </div>

            </div>
        </div>
    </div>

</template>

<script>
    export default {
        name: "editRoomModal",
        data()
        {
            return {
                fields : {},
                error: [],
                accessCode: 'Generate an optional room access code',
            }
        },
        props:{
          'updateRoute':{type:String}
        },
        methods:{
            showModal(record){

                this.fields = record;
                this.accessCode='Generate an optional room access code';
                this.accessCode=(record.access_code)?record.access_code:this.accessCode;
                $('#editModal').modal('show');

               //  this.$refs.editModal.modal('show');
               // console.log(this.$refs.editModal.id)
            },
            generateAccessCode()
            {
                var arr = [];
                while(arr.length < 6){
                    var r = Math.floor(Math.random() * 9) + 1;
                    if(arr.indexOf(r) === -1) arr.push(r);
                }
                this.fields['access_code']=arr.join('');
                this.accessCode ='Access Code : ' +arr.join('');

            },
            deleteAccessCode()
            {
                this.fields['access_code']='';
                this.accessCode= 'Generate an optional room access code'
            },
            roomSubmit()
            {
                this.fields['_method'] = 'PUT';
                url = this.updateRoute.replace(':id',this.fields.id);

                axios.post(url, this.fields
                ).then(data=> {
                    this.$emit('roomAdded',data);
                    this.fields = {};
                    this.accessCode ='Generate an optional room access code';
                    $('#editModal').modal('hide');

                })
                    .catch(error=> {
                        this.error = error.response.data.errors;

                    });

            },
        }
    }
</script>

<style scoped>

</style>