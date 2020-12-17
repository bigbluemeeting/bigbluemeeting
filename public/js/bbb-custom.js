
$('#createRoom').on('click',function () {

        $('#myModal').modal('show')
    });



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
    $('#addPar').on('click', function () {
        var emails = [];
        $('#ul .tag span').each(function (i) {
            emails.push($(this).text());
        });

        $.ajax({
            type: 'POST',
            url: postUrl,
            datatype: 'json',
            data: {
                emails: emails,
                room: $('#room').val(),
                "_token": csrf
            }, success: function success(data) {
                if (data.result.error) {
                    var button = '<button type="button" class="close"  aria-label="Close"> <span aria-hidden="true">&times; </span></button>';
                    $('.errorClass').empty().append(button + data.result.error);
                    $('.errorClass').show();
                }
                if (data.result.success) {

                    url = url.replace(':slug', slug);

                    window.location = url;
                }
            }

        });
    });

    $('#myModal').on('click', '.close', function () {
        $('.errorClass').hide();
    });
    $.fn.tags = function (opts) {
        var selector = this.selector;
        function update($original) {
            var all = [];
            var list = $original.closest(".tags-wrapper").find("li.tag span").each(function () {
                all.push($(this).text());
            });
            all = all.join(",");
            $original.val(all);
        }

        return this.each(function () {
            var self = this,
                $self = $(this),
                $wrapper = $("<div class='tags-wrapper'><ul id='ul'></ul></div>");
            tags = $self.val(), tagsArray = tags.split(","), $ul = $wrapper.find("ul");

            // make sure have opts
            if (!opts) opts = {};
            opts.maxSize = 50;

            // add tags to start
            tagsArray.forEach(function (tag) {
                if (tag) {
                    $ul.append("<li class='tag' name='email[]'><span>" + tag + "</span><a href='#'>x</a></li>");
                }
            });

            // get classes on this element
            if (opts.classList) $wrapper.addClass(opts.classList);

            // add input
            $ul.append("<li class='tags-input'><input type='text' class='tags-secret form-control text-center border' placeholder='Enter Email'/></li>");
            // set to dom
            $self.after($wrapper);
            // add the old element
            $wrapper.append($self);

            // size the text
            var $input = $ul.find("input"),
                size = parseInt($input.css("font-size")) - 4;

            // delete a tag
            $wrapper.on("click", "li.tag a", function (e) {
                e.preventDefault();
                $(this).closest("li").remove();
                $self.trigger("tagRemove", $(this).closest("li").find("span").text());
                update($self);
            });

            // backspace needs to check before keyup
            $wrapper.on("keydown", "li input", function (e) {
                // backspace
                if (e.keyCode == 8 && !$input.val()) {
                    var $li = $ul.find("li.tag:last").remove();
                    update($self);
                    $self.trigger("tagRemove", $li.find("span").text());
                }
                // prevent for tab
                if (e.keyCode == 9) {
                    e.preventDefault();
                }
            });

            // as we type
            $wrapper.on("keyup", "li input", function (e) {
                e.preventDefault();
                $ul = $wrapper.find("ul");
                var $next = $input.next(),
                    usingAutoFill = $next.hasClass("autofill-bg"),
                    $inputLi = $ul.find("li.tags-input");

                // regular size adjust
                $input.width($input.val().length * size);

                // if combined with autofill, check the bg for size
                if (usingAutoFill) {
                    $next.width($next.val().length * size);
                    $input.width($next.val().length * size);
                    // make sure autofill doesn't get too big
                    if ($next.width() < opts.maxSize) $next.width(opts.maxSize);
                    var list = $next.data().data;
                }

                // make sure we don't get too high
                if ($input.width() < opts.maxSize) $input.width(opts.maxSize);

                // tab, comma, enter
                if (!!~[9, 188, 13].indexOf(e.keyCode)) {
                    var val = $input.val().replace(",", "");
                    var otherCheck = true;

                    // requring a tag to be in autofill
                    if (opts.requireData && usingAutoFill) {
                        if (!~list.indexOf(val)) {
                            otherCheck = false;
                            $input.val("");
                        }
                    }

                    // unique
                    if (opts.unique) {
                        // found a match already there
                        if (!!~$self.val().split(",").indexOf(val)) {
                            otherCheck = false;
                            $input.val("");
                            $next.val("");
                        }
                    }

                    // max tags
                    if (opts.maxTags) {
                        if ($self.val().split(",").length == opts.maxTags) {
                            otherCheck = false;
                            $input.val("");
                            $next.val("");
                        }
                    }

                    // if we have a value, and other checks pass, add the tag
                    if (val && otherCheck) {
                        // place the new tag
                        $inputLi.before("<li class='tag'><span>" + val + "</span><a href='#'>x</a></li>");
                        // clear the values
                        $input.val("");
                        if (usingAutoFill) $next.val("");
                        update($self);
                        $self.trigger("tagAdd", val);
                    }
                }
            });
        });
    };
    $("#testInput").tags({
        unique: true,
        maxTags: 100
    });


$('.example').on('click','.InvitedMeetingAttendeeJoin',function () {


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
                    $('.errorClass').empty().append(button+'This room is full  try later.');

                    $('.errorClass').show();
                }
                if (data.url)
                {
                    window.location =data.url;
                }

            },

        });
    });
