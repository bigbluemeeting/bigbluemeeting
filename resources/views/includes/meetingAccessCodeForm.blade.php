<div class="container-fluid mt-4">
    <div class="row bg-light pt-5 pl-4">
        <div class="col-md-12" style="padding-left: 12%;">
            <h6 class="text-left"> You have been invited to join</h6>
            <h2 class="text-left mb-3">
                {{$room->name}}
            </h2>
            <hr class="mt-2 float-left w-25">
        </div>
    </div>
    <div class="row bg-light pt-3">
        <div class="col-md-5"  style="padding-left: 13%;">
            <span class="avatar mb-1" >{{strtoupper(substr($room->user->username,0,1))}}</span>
            <h5 class="font-weight-normal ml-2" style="display: inline-block">{{ucwords($room->user->username)}} (Owner) </h5>
        </div>
        <div class="col-md-6 mt-2" id="frmDiv">
            <form class="text-center" action="{{route('accessCodeResult')}}" method="Post" id="accessFrm">
                @csrf
                <div class="input-group">
                    <input type="text"  name="access_code" class="form-control join-form h-25" placeholder="Enter Room Access Code" autofocus="autofocus" type="text">
                    <input type="hidden" value="{{encrypt($room->url)}}" name="room">
                    <span class="input-group-append">
                                <button class="btn btn-primary btn-sm px-5  join-form" type="submit">
                                    Enter
                                </button>
                            </span>
                </div>
            </form>

            <div id="errorDiv">
                <span class="has-error text-danger float-left mt-3" id="error"></span>
            </div>

            <br><br><br><br>
        </div>
    </div>
</div>