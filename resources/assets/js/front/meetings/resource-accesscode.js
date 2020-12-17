$(document).ready(function () {
    $('#data').on('submit','#accessFrm',function (e) {
        e.preventDefault();
        frmData =$(this).serialize();
        $.post(code,frmData,
            function (data,xhrStatus,xhr) {
                if (data.error){
                    $('#error').html(data.error[0]);
                }else {
                    $('#data').empty().append(data);
                    $( '#data').find('#table').DataTable({
                        pagingType: 'full_numbers',
                        lengthMenu :[[5,10,25,50,-1],[5,10,25,50,'All']],
                        "initComplete": function () {
                            var api = this.api();
                            api.$('td').click( function () {
                                api.search( this.innerHTML ).draw();
                            });
                        }
                    });
                }

            });
    });

    $('#data').on('submit','#frm',function (e) {

        e.preventDefault();
        frmData =$(this).serialize();
        time = setTimeout(function () {
            jsonResult()
        },1000);
    });

    function jsonResult() {
        $.post(join,frmData,
            function (data,xhrStatus,xhr) {
                if (data.error)
                {
                    $('#error').html(data.error[0]);
                    clearInterval(time);
                }
                if (data.notStart)
                {
                    var spinnerHtml = '<div>'+
                        '<h4>This meeting has not started yet.</h4>'+
                        '<p>You will automatically  join when the meeting starts.</p>'+
                        '</div>'+
                        '<span class="input-group-append ml-5 mb-1">'+
                        '<div id="overlay">'+
                        '<div class="cv-spinner">'+
                        '<span class="newspinner"></span>'+
                        '</div>'+
                        '</div>'+
                        '</span>';
                    $('.input-group').html(spinnerHtml)
                    $('#errorDiv').empty();
                    setTimeout(jsonResult,15000)

                }
                if (data.url)
                {
                    window.location =data.url;
                }


            });
    }
});