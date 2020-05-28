<?php

namespace App\Http\Controllers\PublicControllers\Meetings;

use App\bigbluebutton\src\Parameters\CreateMeetingParameters;
use App\Helpers\Helper;
use App\Http\Controllers\Admin\MeetingController;
use App\Http\Controllers\Controller;
use App\Meeting;
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\IsMeetingRunningParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use http\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class  AttendeesMeetingController extends Controller
{
    //

    public function accessCodeResult(Request $request)
    {

        $meeting = Meeting::where('url',decrypt($request->input('room')))
            ->where('access_code',$request->input('access_code'))
            ->first();

        if (empty($meeting))
        {
            return redirect()->back();
        }
        else
        {
            $meeting = Meeting::where('url',decrypt($request->input('room')))
                ->firstOrFail();

            $meeting->access_check = 1;
            $meeting->save();
            return redirect()->to(route('meetings.show',decrypt($request->input('room'))));
        }
    }
    public function joinMeetingAttendee(Request $request)
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



        $room = Meeting::where('url',decrypt($request->input('room')))->firstOrFail();
        $bbb = new BigBlueButton();
        $ismeetingRunningParams =  new IsMeetingRunningParameters(decrypt($request->input('room')));
        $response =$bbb->isMeetingRunning($ismeetingRunningParams);

        if ($response->getRawXml()->running == 'false')
        {
            return response()->json(['notStart'=>true]);
        }
        else{
            $joinMeetingParams = [

                'meetingId'  => decrypt($request->input('room')),
                'username'   => $request->input('name'),
                'password'   => decrypt($room->attendee_password)
            ];
           $url = Helper::joinMeeting($joinMeetingParams);
           return response()->json(['url'=>$url]);


        }


    }

    public function attendeeStartRoom(Request $request)
    {

        $meeting = Meeting::where('url',decrypt($request->input('room')))
            ->firstOrFail();

        $meetingsParams = [

            'meetingUrl' => decrypt($request->input('room')),
            'meetingName' => $meeting->name,
            'attendeePassword' => decrypt($meeting->attendee_password),
            'moderatorPassword' => $meeting->user->password,
            'muteAllUser' => $meeting->mute_on_join,
            'logoutUrl' => '/meetings/'.decrypt($request->input('room')),
            'setRecord' => true,


        ];

            $response = Helper::createMeeting($meetingsParams);
            $joinMeetingParams = [
                    'meetingId'  => decrypt($request->input('room')),
                    'username'   => $request->input('name'),
                    'password'   => decrypt($meeting->attendee_password)
                ];

            $apiUrl = Helper::joinMeeting($joinMeetingParams);
            return redirect()->to($apiUrl);

    }
    public function attendeeJoinAsModerator(Request $request)
    {


        $meeting = Meeting::where('url',decrypt($request->input('room')))
            ->firstOrFail();


        $meetingsParams = [

            'meetingUrl' => decrypt($request->input('room')),
            'meetingName' => $meeting->name,
            'attendeePassword' => decrypt($meeting->attendee_password),
            'moderatorPassword' => $meeting->user->password,
            'muteAllUser' => $meeting->mute_on_join,
            'logoutUrl' => '/meetings/'.decrypt($request->input('room')),
            'setRecord' => true,
        ];
        $response  = Helper::createMeeting($meetingsParams);
        $joinMeetingParams = [

            'meetingId'  => decrypt($request->input('room')),
            'username'   => $request->input('name'),
            'password'   => $meeting->user->password
        ];

        $apiUrl = Helper::joinMeeting($joinMeetingParams);
        return redirect()->to($apiUrl);


    }
}
