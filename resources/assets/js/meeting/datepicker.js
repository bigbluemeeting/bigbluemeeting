$('#createMeeting').on('click', function () {


        $('.addForm')[0].reset();
        $('.picker').val(moment(new Date()).format("YYYY-MM-DD"));
        $('.picker2').val(moment(new Date()).format("YYYY-MM-DD"));
        var startTime = $('#startTime');
        var endTime = $('#endTime');
        startTime.val(moment(new Date(), "h:mm:ss").format("hh:mm A"));
        endTime.val(moment(new Date(), "h:mm:ss").add(10, 'minutes').format("hh:mm A"));

        $('#myModal').modal('show');
    });


    $('#myModal,#editModal').on('click', '.advanceSettings', function () {

        $('.advancedOptions').slideToggle();
    });

    dateTimePickers();

    function dateTimePickers() {
        $('#app').find('#myModal,#editModal').find('.picker').datetimepicker({
            timepicker: false,
            datepicker: true,
            format: 'Y-m-d'
            // formatDate


        });

        $('#app').find('#myModal,#editModal').find('.picker2').datetimepicker({
            timepicker: false,
            datepicker: true,
            format: 'Y-m-d' // formatDate

        });


    }
