<?php

namespace App\Http\Controllers\Admin;

use App\Attendee;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Mail\AttendeeMail;
use App\Meeting;
use App\Room;
use App\User;
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\IsMeetingRunningParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AttendeeController extends Controller
{
    //
    public function index()
    {

    }

    public function create()
    {

    }

    public function store(Request $request)
    {

    }

    public function joinAttendee(Request $request)
    {

        try{
            $meeting = Meeting::where('url',$request->meeting)->firstOrFail();
            $bbb = new BigBlueButton();
            $user = User::findOrFail(Auth::id());
            $ismeetingRunningParams =  new IsMeetingRunningParameters($request->meeting);
            $response =$bbb->isMeetingRunning($ismeetingRunningParams);
            if ($response->getRawXml()->running == 'false')
            {
                return response()->json(['notStart'=>true]);
            }else
            {

                $joinMeetingParams = [

                    'meetingId'  => $request->meeting,
                    'username'   => $user->name,
                    'password'   => decrypt(decrypt($meeting->attendee_password))
                ];

                $url = Helper::joinMeeting($joinMeetingParams);
                return response()->json(['url'=>$url]);
            }
        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }



    }
}
