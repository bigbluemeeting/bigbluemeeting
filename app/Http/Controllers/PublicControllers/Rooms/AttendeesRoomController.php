<?php

namespace App\Http\Controllers\PublicControllers\Rooms;

use App\Http\Controllers\Controller;
use App\Meeting;
use App\Room;
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use Illuminate\Http\Request;
use BigBlueButton\Parameters\IsMeetingRunningParameters;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class AttendeesRoomController extends Controller
{
    //
    public function join(Request $request)
    {




        $validator = Validator::make($request->all(),[
            'name' =>'required'
        ],[
            'name.required' =>'Name Required'
        ]);

        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()->all()]);
        }


        $room = Room::where('url',decrypt($request->input('room')))->firstOrFail();
        $bbb = new BigBlueButton();
        $getMeetingInfoParams = new GetMeetingInfoParameters(decrypt($request->input('room')),decrypt($room->attendee_password));
        $participant = $bbb->getMeetingInfo($getMeetingInfoParams);


        $ismeetingRunningParams =  new IsMeetingRunningParameters(decrypt($request->input('room')));
        $response =$bbb->isMeetingRunning($ismeetingRunningParams);

        if ($response->getRawXml()->running == 'false')
        {

            return response()->json(['notStart'=>true]);

        }
        else{

            if ($room->maximum_people > $participant->getRawXml()->participantCount )
            {

                $joinMeetingParams = new JoinMeetingParameters(decrypt($request->input('room')), $request->input('name'),decrypt($room->attendee_password));
                $joinMeetingParams->setRedirect(true);
//                $joinMeetingParams->guest('true');
                $url = $bbb->getJoinMeetingURL($joinMeetingParams);
                return response()->json(['url'=>$url]);
            }
            else{

                return response()->json(['full'=>true]);

            }


        }


    }

    public function authAttendeeJoin(Request $request)
    {

        $room = Room::where('url',$request->meeting)->firstOrFail();

        $bbb = new BigBlueButton();

        $getMeetingInfoParams = new GetMeetingInfoParameters($request->meeting,decrypt($room->attendee_password));
        $participant = $bbb->getMeetingInfo($getMeetingInfoParams);

        $ismeetingRunningParams =  new IsMeetingRunningParameters($request->meeting);
        $response =$bbb->isMeetingRunning($ismeetingRunningParams);

        if ($response->getRawXml()->running == 'false')
        {

            return response()->json(['notStart'=>true]);
        }
        else{

            if ($room->maximum_people > $participant->getRawXml()->participantCount )
            {
                $attendeePassword = 'attendeePassword';
                $joinMeetingParams = new JoinMeetingParameters($request->meeting,Auth::user()->username,decrypt($room->attendee_password));
                $joinMeetingParams->setRedirect(true);
//                $joinMeetingParams->guest('true');
                $url = $bbb->getJoinMeetingURL($joinMeetingParams);
                return response()->json(['url'=>$url]);
            }
            else{

                return response()->json(['full'=>true]);
            }


        }


    }
}
