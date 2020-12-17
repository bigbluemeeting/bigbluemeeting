$(document).ready(function () {
    var time;
    var frmData;
    var table =  $('#table').DataTable({
        pagingType: 'full_numbers',
        lengthMenu :[[5,10,25,50,-1],[5,10,25,50,'All']],
        "language": {
            "emptyTable": "No Recording Available"
        },

        "initComplete": function () {
            var api = this.api();
            api.$('td').click( function () {
                api.search( this.innerHTML ).draw();
            });
        },
        "fnDrawCallback":function(){
            if($("#tablesorter").find("tr:not(.ui-widget-header)").length==0){
                $('#table_wrapper .row:first').remove();
                $('#table_wrapper .row:last').remove();
            }
        }
    });

    var InvitedMeetingTable =  $('#InvitedMeetingTable').DataTable({
        pagingType: 'full_numbers',
        lengthMenu :[[5,10,25,50,-1],[5,10,25,50,'All']],
        "initComplete": function () {
            var api = this.api();
            api.$('td').click( function () {
                api.search( this.innerHTML ).draw();
            });
        }
    });


    // $('#data').on('submit','#joinfrm',function (e) {
    //
    //     e.preventDefault();
    //     frmData =$(this).serialize();
    //     time = setTimeout(function () {
    //         jsonResult()
    //     },1000);
    // });
    //
    // function jsonResult() {
    //     $.post(joinYou,frmData,
    //         function (data,xhrStatus,xhr) {
    //             if (data.error)
    //             {
    //                 console.log(data.error[0]);
    //                 $('#error').html(data.error[0]);
    //                 clearInterval(time);
    //             }
    //             if (data.notStart)
    //             {
    //                 $('.input-group').html(`
    //                                             <div>
    //                                             <h4>This meeting hasn't started yet.</h4>
    //                                             <p>You will automatically  join when the meeting starts.</p>
    //                                             </div>
    //                                         <span class="input-group-append ml-5 mb-1">
    //                                         <div id="overlay">
    //                                             <div class="cv-spinner">
    //                                                 <span class="newspinner"></span>
    //                                             </div>
    //                                         </div>
    //                                         </span>`)
    //                 $('#errorDiv').empty();
    //                 setTimeout(jsonResult,15000)
    //
    //             }
    //             if (data.url)
    //             {
    //                 window.location =data.url;
    //             }
    //
    //
    //         });
    // }
});
