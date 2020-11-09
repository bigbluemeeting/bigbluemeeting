<template>
    <div id="DeleteModal" class="modal fade text-danger" role="dialog">
        <div class="modal-dialog">
            <form @submit="deleteMeeting" method="POST"  id="deleteForm">
                <div class="modal-content">
                    <div class="modal-header bg-danger">

                        <h4 class="modal-title text-center">DELETE CONFIRMATION</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p class="text-center">Are You Sure Want To Delete ?</p>
                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
                        <input type="submit" name="" class="btn btn-danger btnDelete" value="Yes, Delete">

                    </div>
                </div>
            </form>
        </div>
    </div>

</template>

<script>
    import {eventBus} from "../../app.js";

    export default {
        name: "deleteModal",
        props :['deleteRoute'],

        data()
        {
            return{
                deleteFields :{},
                delete_id :'',
                upcoming :false,
            }
        },
        methods :{
            deleteMeeting:function(e)
            {
                e.preventDefault();
                axios.post(this.deleteRoute, {

                    'id' : this.delete_id,
                    'type' : this.upcoming,

                }).then(data=> {
                    if (this.upcoming)
                    {
                        eventBus.upcomingMeetings(data);
                    }else{
                        eventBus.pastMeetings(data);
                    }

                    $('#DeleteModal').modal('hide');

                }).catch(error => this.error = error.response.data.errors);

            },

        },
        created()
        {
             eventBus.$on("deleteId", (myData) => {

                 this.delete_id = myData['id'];
                 this.upcoming =myData['upcoming'];
                 $('#DeleteModal').modal('show');

        });
        }

    }
</script>

<style scoped>

</style>