
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

    </style>
</head>
<body>
<br><br>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <table width='100%' style='font-size: 14px; font-weight: 300; color: #4A4A4A;'>
                <tr>
                    <td>
                        <div>
                            <b>{!!  $meetingParams['mailParams']['header']!!}</b>

                        </div>
                        <div style="margin-top: 20px">
                            <b>
                                If You Want to make your meeting please click here
                                <a href="{{$url}}" >
                                    Signup.
                                </a>
                                If you already have a account, please click here
                                <a href="{{route('login')}}"  >
                                    Login
                                </a>

                            </b>
                            <br>
                        </div>


                    </td>
                </tr>

                <tr>

                    <td>
                        <div style="margin-top: 20px;">
                            <b>{!!  $meetingParams['mailParams']['footer']!!}</b>
                        </div>
                    </td>


                </tr>

            </table>
        </div>
    </div>
</div>


</body>
</html>
