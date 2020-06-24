$('.btnDeleteConfirm').on('click', function () {

    id = $(this).data('item');
    deleteData(id);
});
function deleteData(id) {
    $('.task-input').val(id);

}
$('.btnDelete').on('click', function (e) {

    var token=$('input[name=_token]').val();
    var method = $('input[name=_method]').val();
    var id = $('.task-input').val();
    url = action.replace(':id', id);

    $.ajax(
        {
            url: url,
            type: 'DELETE',
            dataType: "JSON",
            data: {
                "id": id,
                "_method": method,
                "_token": token,
            },
            success: function success(data)
            {

                window.location = currentUrl;
            }
        });

});