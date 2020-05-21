@if(count($errors))
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <ol>
            @foreach($errors->all() as $error)
                <li>{{$error}}</li>
{{--                <></>--}}
            @endforeach
        </ol>
    </div>
@endif