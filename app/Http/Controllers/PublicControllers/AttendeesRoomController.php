<?php

namespace App\Http\Controllers\PublicControllers;

use App\Http\Controllers\Controller;
use App\Room;
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use Illuminate\Http\Request;
use BigBlueButton\Parameters\IsMeetingRunningParameters;
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

        $bbb = new BigBlueButton();
        $room = Room::where('url',decrypt($request->input('room')))->firstOrFail();


        $getMeetingInfoParams = new GetMeetingInfoParameters(decrypt($request->input('room')),decrypt($request->input('user')));
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
                $attendeePassword = 'attendeePassword';
                $joinMeetingParams = new JoinMeetingParameters(decrypt($request->input('room')), $request->input('name'),$attendeePassword);
                $joinMeetingParams->setRedirect(true);
                $url = $bbb->getJoinMeetingURL($joinMeetingParams);
                return response()->json(['url'=>$url]);
            }
            else{

                return response()->json(['full'=>true]);

            }


        }


    }
}
