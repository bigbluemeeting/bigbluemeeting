<!DOCTYPE html>
<html>
<head>
    <title>Invitation For Meeting</title>
</head>

<body>
<h2>Welcome to the site </h2>
<br/>
You are for this meeting  {{$meeting}} from {{$from}}
<br/>
<form>

    <a href="{{url('signup/' .$meeting_id. '/' . $to)}}">SignUp For Attend Meeting</a>
</form>

</body>

</html>