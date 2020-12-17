$('.btnDeleteConfirm').on('click', function () {

        id = $(this).data('item');
        deleteData(id);
    });
    function deleteData(id) {
        $('.task-input').val(id);
    }
    $('.btnDelete').on('click', function (e) {

        var token = $('input[name=_token]').val();
        var method = $('input[name=_method]').val();
        var id = $('.task-input').val();
        url = action.replace(':id', id);

        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: "JSON",
            data: {
                "id": id,
                "_method": method,
                "_token": token
            },
            success: function success(data) {

                window.location = currentUrl;
            }
        });
    });
    $('.main-container').on('click','.btnAddMeeting',function () {

        $('.meeting-file-name').val($(this).data('item'))
        $('.meetingHeader').html($(this).data('task'))
        $('#meetingFilesAddModal').modal('show')

    });
    $('.main-container').on('click','.btnAddRoom',function () {

        $('.room-file-name').val($(this).data('item'))
        $('.roomHeader').html($(this).data('task'))
        $('#roomFilesAddModal').modal('show')
    });
    $('.btnDeleteConfirm').on('click',function () {
        $('#DeleteModal').modal('show')
    });
