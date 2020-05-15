<?php

namespace App\Http\Controllers\Admin;

use App\Attendee;
use App\Http\Controllers\Controller;
use App\Mail\AttendeeMail;
use App\Meeting;
use App\User;
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\IsMeetingRunningParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AttendeeController extends Controller
{
    //
    public function index()
    {
       return $this->create();
    }

    public function create()
    {
        $pageName  = "Add Attendee";

        $user =User::FindOrFail(Auth::id());
        $per = $user->getAllPermissions()->pluck('name')->toArray();

        if (in_array('master_manage',$per))
        {
            $meetingsList = Meeting::pluck('name','url')->all();

            return view('admin.attendees.create',compact('meetingsList','pageName'));

        }
        if (in_array('users_manage',$per))
        {
            $meetingsList = Meeting::pluck('name','id')->all();

            return view('admin.attendees.create',compact('meetingsList','pageName'));
        }
        if (in_array('moderate',$per))
        {
            $meetingsList = $user->meetings()->pluck('name','id')->all();

            return view('admin.attendees.create',compact('meetingsList','pageName'));

        }

    }

    public function store(Request $request)
    {

        $this->validate($request,[
            'email' => 'required|email' ,
            'meeting_id'=>'required'
        ]);

        $email = $request->input('email');
        $user = User::where('email',$email)->first();
        $data=$request->all();
        $meeting = Meeting::where('url',$request->input('meeting_id'))->firstOrFail();
        if (!empty($user))
        {
            $data['user_id'] = $user->id;
        }
        else
            {
                $user = User::findOrFail(Auth::id());
                Mail::to($data['email'])->send(new AttendeeMail([

                    'toEmail' => encrypt($data['email']),
                    'fromEmail' =>  $user->email,
                    'meeting_name'=> $meeting->name,
                    'meeting_id'=>encrypt($request->input('meeting_id')),
                ]));


                return $this->create();
            }




        $attendee = Attendee::create(['email'=>$data['email'],'user_id'=>$data['user_id']]);

        $attendee->meetings()->attach($meeting->id);

        return $this->create();

    }

    public function joinAttendee(Request $request)
    {




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

            $joinMeetingParams = new JoinMeetingParameters($request->meeting, $user->name, decrypt(decrypt($meeting->attendee_password)));
            $joinMeetingParams->setRedirect(true);
            $url = $bbb->getJoinMeetingURL($joinMeetingParams);
            return response()->json(['url'=>$url]);
        }

    }
}
