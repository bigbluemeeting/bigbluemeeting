$('.meeting-rooms').on('click','.InvitedMeetingAttendeeJoin',function () {


    var button=null;
        $.ajax({
            type:'POST',
            url:attendeeJoinUrl,
            datatype:'json',
            data:{
                meeting:$(this).data('id'),
                "_token":csrf
            },success:function (data) {

                button='<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times; </span></button>';

                if (data.notStart)
                {
                    $('.errorClass').append(button+'This room not started please contact meeting Owner or try later.');

                    $('.errorClass').show();
                }
                if (data.full)
                {
                    $('.errorClass').empty().append(button+'This room is full try later.');

                    $('.errorClass').show();
                }
                if (data.url)
                {
                    window.location =data.url;
                }

            },

        });
    });
