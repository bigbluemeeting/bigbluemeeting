<template>
<div id="meetings-file-app">
    <table class="table table-hover" v-if="files.total > 0">
        <tbody>
        <tr>
            <th>File</th>
            <th>Date</th>
            <th>Mime</th>
            <th>Size</th>
            <td>Action</td>
        </tr>



        <tr v-for="file in files.data" :class="['row-data-' + file.id]">
            <td><a href="">{{file.name}}</a></td>
            <td>{{moment(file.upload_date)}}</td>
            <td>{{file.type}}</td>
            <td>{{formatBytes(file.size)}}</td>
            <td>
                <span  data-toggle="modal"  :data-item = file.id @click="showModal(file.id)" class="btn btn-sm btn-danger-outline btnDeleteConfirm"><i class="fa fa-trash"></i> Delete</span>

            </td>
        </tr>

        </tbody>
    </table>
    <div class="row">
        <div class="col-sm-6 col-sm-offset-5 paginate">
            <pagination :data="files" @pagination-change-page="getResults"></pagination>

        </div>
    </div>

    <!--DELETE FILE MODEL  -->
    <div id="DeleteModal" class="modal fade text-danger" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header bg-danger">

                    <h4 class="modal-title text-center">DELETE CONFIRMATION</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                    <p class="text-center">Are You Sure Want To Delete ?</p>
                </div>
                <div class="modal-footer">

                    <input type="hidden" value="" name="task" class="task-input ">
                    <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
                    <button type="button"  class="btn btn-danger" @click="deleteFile">Yes, Delete</button>

                </div>
            </div>

        </div>
    </div>
</div>
</template>

<script>
    Vue.component('pagination', require('laravel-vue-pagination'));
    export default {
        name: "meetingFiles",
        props:{
            meetingUrl :{
                type:String,
                required:true
            },
            fileRoute :{
                type:String,
                required:true
            },
            deleteFileRoute:{
                type:String,
                required:true
            },
        },
        data()
        {
            return {
                files :{},
                showData :false,
                fileData:{
                    id:null,
                    url:null,
                }

            }
        },

        created() {
            var route= this.fileRoute.replace(':url',this.meetingUrl)


            axios.get(route)
                .then(response=>{
                   this.files =response.data.files

                    this.showData=true


                })
                .catch(error=>console.log(error))

        },
        methods:{
            moment:function (date) {
                return moment(date).format('MMM D,YYYY h:mm A');

            },
            formatBytes(bytes, decimals = 2) {
                if (bytes === 0) return '0 Bytes';

                const k = 1024;
                const dm = decimals < 0 ? 0 : decimals;
                const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

                const i = Math.floor(Math.log(bytes) / Math.log(k));

                return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
            },
            getResults(page = 1) {
                var route= this.fileRoute.replace(':url',this.meetingUrl)

                axios.get(route+'?page=' + page)

                    .then(response =>this.files=response.data.files).catch(error=>console.log(error));
            },
            showModal(id)
            {
                this.fileData.id=id
                this.fileData.url=this.meetingUrl;
                $('#DeleteModal').modal('show');
            },
            deleteFile()
            {

                axios.post(this.deleteFileRoute, this.fileData
                ).then(response=> {

                    this.files =response.data.files

                    $('#DeleteModal').modal('hide');


                })
                    .catch(error=> {
                        console.log( error.response.data.errors);


                    });


            }
        }
    }
</script>

<style scoped>

</style>