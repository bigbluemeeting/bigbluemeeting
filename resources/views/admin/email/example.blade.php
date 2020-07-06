{{--<!DOCTYPE html>--}}
{{--<html>--}}
{{--<head>--}}
{{--    <title>Invitation For Meeting</title>--}}
{{--</head>--}}

{{--<body>--}}

{{--<div>--}}
{{--    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">--}}
{{--        <tr>--}}
{{--            <td>&nbsp;</td>--}}
{{--            <td class="container">--}}
{{--                <div class="content">--}}
{{--                    <!-- START CENTERED WHITE CONTAINER -->--}}
{{--                    <table role="presentation" class="main">--}}
{{--                        <!-- START MAIN CONTENT AREA -->--}}
{{--                        <tr>--}}
{{--                            <td class="wrapper">--}}
{{--                                <table role="presentation" border="0" cellpadding="0" cellspacing="0">--}}
{{--                                    <tr>--}}
{{--                                        <td>--}}
{{--                                            <p>{!!  $meetingParams['mailParams']['header']!!}</p>--}}
{{--                                            <p>Sometimes you just want to send a simple HTML email with a simple design and clear call to action. This is it.</p>--}}
{{--                                            <hr>--}}
{{--                                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">--}}
{{--                                                <tbody>--}}
{{--                                                <tr>--}}
{{--                                                    <td align="left">--}}
{{--                                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0">--}}
{{--                                                            <tbody>--}}
{{--                                                            <tr>--}}
{{--                                                                <td>  </td>--}}
{{--                                                            </tr>--}}
{{--                                                            </tbody>--}}
{{--                                                        </table>--}}
{{--                                                    </td>--}}
{{--                                                </tr>--}}
{{--                                                </tbody>--}}
{{--                                            </table>--}}
{{--                                            <p>{!!  $meetingParams['mailParams']['footer']!!}</p>--}}

{{--                                        </td>--}}
{{--                                    </tr>--}}
{{--                                </table>--}}
{{--                            </td>--}}
{{--                        </tr>--}}

{{--                        <!-- END MAIN CONTENT AREA -->--}}
{{--                    </table>--}}
{{--                    <!-- END CENTERED WHITE CONTAINER -->--}}

{{--                    <!-- START FOOTER -->--}}
{{--                    <div class="footer">--}}
{{--                        <table role="presentation" border="0" cellpadding="0" cellspacing="0">--}}
{{--                            <tr>--}}
{{--                                <td class="content-block">--}}
{{--                                    <span class="apple-link"></span>--}}
{{--                                    <br>  <a href="http://i.imgur.com/CScmqnj.gif">Unsubscribe</a>.--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td class="content-block powered-by">--}}
{{--                                    Powered by <a href="http://htmlemail.io">HTMLemail</a>.--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                        </table>--}}
{{--                    </div>--}}
{{--                    <!-- END FOOTER -->--}}

{{--                </div>--}}
{{--            </td>--}}
{{--            <td>&nbsp;</td>--}}
{{--        </tr>--}}
{{--    </table>--}}

{{--</div>--}}
{{--</body>--}}

{{--</html>--}}
        <!doctype html>
<html>

<head>

    <link rel="stylesheet" href="{{ asset('css/vendor.css') }}">
    <style>

        a {
            text-decoration: none
        }

        hr{
            width: 81%;
            display: inline-block;
            margin-top: 30px;
        }
        .col-md-6{
            margin-left: 20px;

        }
        a.button1{
             display:inline-block;
             padding:0.35em 1.2em;
             border:0.1em solid #FFFFFF;
             margin:0 0.3em 0.3em 0;
             border-radius:0.12em;
             box-sizing: border-box;
             text-decoration:none;
             font-family:'Roboto',sans-serif;
             font-weight:300;
             color:#FFFFFF;
             text-align:center;
             transition: all 0.2s;
        }
        a.button1:hover{

             color:#000000;
             background-color:#FFFFFF;
        }
        @media all and (max-width:30em){
             a.button1{
                  display:block;
                  margin:0.4em auto;
                 }
        }

    </style>
</head>

<body> <br><br>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table>
                    <tr>
                        <td>
                            <div class="col-md-6">
                                <b>You are invited to attend an online meeting by [user:email].
                                    What? An online meeting titled [meeting:name].
                                    When? The meetings will start at [meeting:start] (UTC) and will end at [meeting:end]

                                    You may join the meeting, respond to the invitation, and get local time by going to the following URL:

                                    [meeting:url]

                                    If you need to get a hold of the meeting coordinator for any reason, simply reply to this e-mail, the reply will be addressed to his or her e-mail.
                                </b>

                            </div>
                            <hr>

                            <div class="col-md-6">
                                <b>If you do not wish to receive ANY messages from us including any future invites please click this link: [unsubscribe:link]

                                    If you have asked us to stopped sending you mail but now have changed your mind click here: [subscribe:link]</b>
                            </div>


                    </tr>




                </table>
            </div>

        </div>
    </div>
</div>

</body>
</html>
