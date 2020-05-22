<?php

namespace App\Http\Controllers\PublicControllers\Meetings;

use App\bigbluebutton\src\Parameters\CreateMeetingParameters;
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

    public function checkCode($url)
    {

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
        $getMeetingInfoParams = new GetMeetingInfoParameters(decrypt($request->input('room')),decrypt($room->attendee_password));
        $participant = $bbb->getMeetingInfo($getMeetingInfoParams);



        $ismeetingRunningParams =  new IsMeetingRunningParameters(decrypt($request->input('room')));
        $response =$bbb->isMeetingRunning($ismeetingRunningParams);

        if ($response->getRawXml()->running == 'false')
        {

            return response()->json(['notStart'=>true]);

        }
        else{

            if ($room->all_join_moderator)
            {
              dd($room->user->password);
            }

            $joinMeetingParams = new JoinMeetingParameters(decrypt($request->input('room')), $request->input('name'),decrypt($room->attendee_password));
            $joinMeetingParams->setRedirect(true);
//                $joinMeetingParams->guest('true');
            $url = $bbb->getJoinMeetingURL($joinMeetingParams);
            return response()->json(['url'=>$url]);


        }


    }

    public function attendeeStartRoom(Request $request)
    {

        $meeting = Meeting::where('url',decrypt($request->input('room')))
            ->firstOrFail();


//        dd(decrypt($meeting->attendee_password));

        $bbb = new BigBlueButton();
        $createMeetingParams = new CreateMeetingParameters(decrypt($request->input('room')) , $meeting->name);
        $createMeetingParams->setLogoutUrl('/meetings/'.decrypt($request->input('room')));
        $createMeetingParams->setRecord(true);
        $createMeetingParams->setAttendeePassword(decrypt($meeting->attendee_password));
        $createMeetingParams->setModeratorPassword($meeting->user->password);
        $createMeetingParams->setMuteOnStart($meeting->mute_on_join);
        $createMeetingParams->setLockSettingsDisableMic($meeting->mute_on_join);
        $createMeetingParams->setAllowStartStopRecording(true);

        $response = $bbb->createMeeting($createMeetingParams);


        $joinMeetingParams = new JoinMeetingParameters(decrypt($request->input('room')), $request->input('name'), decrypt($meeting->attendee_password));
        $joinMeetingParams->setRedirect(true);

        $apiUrl = $bbb->getJoinMeetingURL($joinMeetingParams);
        return redirect()->to($apiUrl);



    }
    public function attendeeJoinAsModerator(Request $request)
    {


        $meeting = Meeting::where('url',decrypt($request->input('room')))
            ->firstOrFail();


//        dd(decrypt($meeting->attendee_password));

        $bbb = new BigBlueButton();
        $createMeetingParams = new CreateMeetingParameters(decrypt($request->input('room')) , $meeting->name);
        $createMeetingParams->setLogoutUrl('/meetings/'.decrypt($request->input('room')));
        $createMeetingParams->setRecord(true);
        $createMeetingParams->setAttendeePassword(decrypt($meeting->attendee_password));
        $createMeetingParams->setModeratorPassword($meeting->user->password);
        $createMeetingParams->setMuteOnStart($meeting->mute_on_join);
        $createMeetingParams->setLockSettingsDisableMic($meeting->mute_on_join);
        $createMeetingParams->setAllowStartStopRecording(true);

        $response = $bbb->createMeeting($createMeetingParams);


        $joinMeetingParams = new JoinMeetingParameters(decrypt($request->input('room')), $request->input('name'), $meeting->user->password);
        $joinMeetingParams->setRedirect(true);

        $apiUrl = $bbb->getJoinMeetingURL($joinMeetingParams);
        return redirect()->to($apiUrl);


    }
}
