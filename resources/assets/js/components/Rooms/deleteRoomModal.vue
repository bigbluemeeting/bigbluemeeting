<template>
    <div id="DeleteModal" class="modal fade text-danger" role="dialog">
        <div class="modal-dialog">
            <form @submit.prevent="deleteRoom" method="POST"   id="deleteForm">
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
    export default {

        name: "deleteRoomModal",
        props:["deleteRoomRoute"],
        methods:{
            deleteRoom:function (e) {

                axios.delete(this.deleteRoomRoute).then(data=> {

                    this.$emit('roomAdded',data);
                    $('#DeleteModal').modal('hide');

                }).catch(error => this.error = error.response.data.errors);

            }
        },

    }
</script>

<style scoped>

</style>