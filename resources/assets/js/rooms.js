
$('#createRoom').on('click',function () {

    $('.addForm')[0].reset();
    $('.picker').val(moment(new Date()).format("YYYY-MM-DD"));
    $('.picker2').val(moment(new Date()).format("YYYY-MM-DD"));
    var  startTime =$('#startTime');
    var  endTime =$('#endTime');
    startTime.val(moment(new Date(), "h:mm:ss").format("hh:mm A"));
    endTime.val(moment(new Date(), "h:mm:ss").add(10,'minutes').format("hh:mm A"));


    $('#myModal').modal('show');
});
$('.btn-manage').on('click',function () {
    var id = $(this).data('task');

    url=url.replace(':id',id);

    $('.manageForm')[0].reset();
    $.get(url,function (data) {


        action = action.replace(':id',data.result.id);
        $('.manageForm').prop('action',action);
        startDate = moment(new Date(data.result.start_date)).format("YYYY-MM-DD");
        endDate  =  moment(new Date(data.result.end_date)).format("YYYY-MM-DD");
        startTime =  moment(new Date(data.result.start_date),"h:mm:ss").format("hh:mm A");
        endTime =  moment(new Date(data.result.end_date),"h:mm:ss").format("hh:mm A");
        $('#edit-room-name').val(data.result.name);
        $('#edit-max-people').val(data.result.maximum_people);
        $('.editPicker').val(startDate);
        $('.editPicker2').val(endDate);
        $('.startTime').val(startTime);
        $('.endTime').val(endTime);
        $('.meeting_description').val(data.result.meeting_description);
        $('.welcome_message').val(data.result.welcome_message);



        if (data.result.mute_on_join)
        {
            $('.mute_on_join').prop("checked","checked");

        }

        if (data.result.require_moderator_approval)
        {
            $('.require_moderator_approval').prop("checked","checked");

        }

        $(".meeting_record option").each(function(){


            if ($(this).val()== data.result.meeting_record)
                $(this).attr("selected","selected");
        });


        $('#editModal').modal('show');


    });

});


$('#modal').on('click','.advanceSettings',function () {
    $('.advancedOptions').slideToggle()
});

dateTimePickers();
function dateTimePickers() {
    $('#modal').find('#myModal,#editModal').find('.picker').datetimepicker({
        timepicker: false,
        datepicker: true,
        format: 'Y-m-d',
        // formatDate


    });

    $('#modal').find('#myModal,#editModal').find('.picker2').datetimepicker({
        timepicker: false,
        datepicker: true,
        format: 'Y-m-d', // formatDate

    });

    $('.clockpicker1').clockpicker({
        autoclose: true,
        twelvehour: true,
        placement: 'bottom',
        align: 'left',
        vibrate:true,

    });

    $('.clockpicker2').clockpicker({
        autoclose: true,
        twelvehour: true,
        placement: 'top',
        align: 'left',
        vibrate:true,

    });
}
$('.btnDeleteConfirm').on('click',function () {


    id = $(this).data('item');
    deleteData(id)
});
function deleteData(id)
{
    url = action.replace(':id', id);
    $("#deleteForm").prop('action', url);
}
$('.btnDelete').on('click',function () {
    $("#deleteForm").submit();
});