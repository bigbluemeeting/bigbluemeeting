<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\bbbHelpers;
use App\Http\Controllers\Controller;
use App\Meeting;
use App\User;
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\IsMeetingRunningParameters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

                $url = bbbHelpers::joinMeeting($joinMeetingParams);
                return response()->json(['url'=>$url]);
            }
        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }



    }
}
