<?php

namespace App\Http\Controllers\PublicControllers\Rooms;

use App\Attendee;
use App\Files;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Mail\AttendeeMail;
use App\Meeting;
use App\Notifications\InviteParticipantMail;
use App\Room;
use App\User;
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\EndMeetingParameters;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class RoomsController extends Controller
{
    //

    protected $logoutUrl = '/rooms/';
    protected $meetingsParams = [];

    public function index()
    {
//        https://c38e6.bigbluemeeting.com/bigbluebutton/
//       QFCwDfZr6PqO9Utwd82LcUJMfAJt5OjfsftlrPiRrQ
        $pageName='Rooms List';

        $user = User::findOrFail(Auth::id());
        $currentDate  = Carbon::now(Helper::get_local_time())->format('yy-m-d H:i');

        $pastMeetings =$user->rooms()
            ->where('end_date','<',$currentDate)
            ->orderBy('id','DESC')
            ->paginate(10);




        $upComingMeetings =  $user->rooms()
            ->where('end_date','>=',$currentDate)
            ->orderBy('id','DESC')
            ->paginate(10);



        return view('public.rooms.index',compact('pageName','upComingMeetings','pastMeetings'));
    }

    public function store(Request $request)
    {


        $rules = [
            'name' => 'required|max:50',
            'maximum_people' => 'required|integer|min:2',
            'meeting_description' =>'required',
            'welcome_message' =>'required',
            'startTime' => 'date_format:h:i A'
        ];
        $message =[
            'name.required' =>'Meeting Name Required',
            'name.max' =>'Maximum 50 Characters Allowed For Meeting Name',
            'maximum_people.required' =>'Maximum People Required',
            'maximum_people.integer' =>'Only Numbers Accepted',
            'maximum_people.min' =>'Minimum Two Person Required For Meeting',
            'meeting_description.required' =>'Meeting Description Required',
            'welcome_message.required' =>'Welcome Message Required For Meeting'

        ];

        $request->validate($rules,$message);
        $this->saveRoomToDb($request);
        return $this->createMeeting('create');
    }
    public function edit(Room $room)
    {
        return response()->json(['result' =>$room]);

    }

    public function update(Request $request,Room $room)
    {

        $rules = [
            'name' => 'required|max:50',
            'maximum_people' => 'required|integer|min:2',
            'meeting_description' =>'required',
            'welcome_message' =>'required',

        ];
        $message =[
            'name.required' =>'Meeting Name Required',
            'name.max' =>'Maximum 50 Characters Allowed For Meeting Name',
            'maximum_people.required' =>'Maximum People Required',
            'maximum_people.integer' =>'Only Numbers Accepted',
            'maximum_people.min' =>'Minimum Two Person Required For Meeting',
            'meeting_description.required' =>'Meeting Description Required',
            'welcome_message.required' =>'Welcome Message Required For Meeting'

        ];
        $request->validate($rules,$message);
        $this->updateRooms($request,$room);

        return redirect()->back()->with(['success'=>'Room Updated Successfully']);

    }

    public function saveRoomToDb($request)
    {
        $startDate = Carbon::createFromFormat('yy-m-d',$request->input('start_date'))->toDateString();
        $startTime =Carbon::parse($request->input('startTime'))->format('H:i');
        $start_date = $startDate.' '.$startTime;
        $endDate =  Carbon::createFromFormat('yy-m-d',$request->input('end_date'))->toDateString();
        $endTime =  Carbon::parse($request->input('endTime'))->format('H:i');
        $end_date = $endDate.' '.$endTime;
        $data= $request->except('startTime','endTime');
        $data['user_id'] = Auth::id();
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['attendee_password'] = encrypt(Auth::id().Str::random(2).'attendeePassword');

        $room = Room::create($data);
        $user = User::findOrFail(Auth::id());
        $room->url =strtolower($user->name).'-'.Str::random(3).'-'.$room->id.Str::random(2);
        $room->save();
        $this->logoutUrl = url($this->logoutUrl).'/'.$room->url;
        $this->meetingsParams = [
            'meetingUrl' =>  $room->url,
            'meetingName' => $request->input('name'),
            'attendeePassword' => decrypt($data['attendee_password']),
            'moderatorPassword' => $user->password,
            'welcome_message' =>$request->input('welcome_message'),
            'setRecord' =>$room->meeting_record,
            'logoutUrl' => $this->logoutUrl,
            'muteAllUser' =>  $request->has('mute_on_join') ? true :false,
            'moderator_approval' => $request->has('require_moderator_approval') ? true :false

        ];

    }

    public function updateRooms($request,$room)
    {
        $startDate = Carbon::createFromFormat('yy-m-d',$request->input('start_date'))->toDateString();
        $startTime =Carbon::parse($request->input('startTime'))->format('H:i');
        $start_date = $startDate.' '.$startTime;
        $endDate =  Carbon::createFromFormat('yy-m-d',$request->input('end_date'))->toDateString();
        $endTime =  Carbon::parse($request->input('endTime'))->format('H:i');
        $end_date = $endDate.' '.$endTime;
        $data= $request->except('startTime','endTime','commit');
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $request->has('mute_on_join') ? $data['mute_on_join'] :$data['mute_on_join'] = 0;
        $request->has('require_moderator_approval') ? $data['require_moderator_approval'] :$data['require_moderator_approval'] =0 ;
        $room->update($data);
        $user = Auth::user();
        $bbb = new BigBlueButton();
        $getMeetingInfoParams = new GetMeetingInfoParameters($room->url,$user->password);
        $response = $bbb->getMeetingInfo($getMeetingInfoParams);

        $this->logoutUrl = url($this->logoutUrl.'/'.$room->url);
        $this->meetingsParams = [
            'meetingUrl' =>  $room->url,
            'meetingName' => $request->input('name'),
            'attendeePassword' => decrypt($room->attendee_password),
            'moderatorPassword' => $user->password,
            'welcome_message' =>$request->input('welcome_message'),
            'setRecord' =>$room->meeting_record,
            'logoutUrl' => $this->logoutUrl,
            'muteAllUser' =>  $request->has('mute_on_join') ? true :false,
            'moderator_approval' => $request->has('require_moderator_approval') ? true :false

        ];
        if ($response->getReturnCode() == 'FAILED')
        {
            return $this->createMeeting('create');
        }
        else{

            $endMeetingParams = new EndMeetingParameters($room->url,$user->password);
            $response = $bbb->endMeeting($endMeetingParams);
            return $this->createMeeting('create');
        }

    }

    public function createMeeting($name=null)
    {

        $response = Helper::createMeeting($this->meetingsParams);

        if ($response->getReturnCode() == 'FAILED') {
            return 'Can\'t create room! please contact our administrator.';

        } else {

            if ($name== 'create')
            {
                return $this->index();
            }
            else
            {
                $joinMeetingParams = [

                    'meetingId'  => $this->meetingsParams['meetingUrl'],
                    'username'   => $this->meetingsParams['username'],
                    'password'   => $this->meetingsParams['moderatorPassword']
                ];

                $apiUrl = Helper::joinMeeting($joinMeetingParams);
                return redirect()->to($apiUrl);

            }

        }


    }

    public function show($url)
    {

        $room = Room::where('url',$url)->firstOrFail();
        if (Auth::check())
        {
            $response = Gate::allows('view',$room);
            if ($response)
            {
                $pageName = ucwords($room->name);
                return view('public.rooms.auth.single',compact('pageName','room'));
            }
            else{
                return redirect()->to(route('invitedMeetings'));
//                return $this->inviteAttendee();
            }


        }else{

            $roomsRecordingList = Helper::recordingLists($url);
            $pageName = ucwords($room->user->username);
            return view('public.rooms.notauth.join',compact('pageName','room','roomsRecordingList'));
        }
    }

    public function join(Request $request)
    {
        $room = Room::where('url',decrypt($request->input('room')))->firstOrFail();
        $bbb = new BigBlueButton();
        $user = User::findOrFail(Auth::id());
        $getMeetingInfoParams = new GetMeetingInfoParameters(decrypt($request->input('room')),$user->password);
        $response = $bbb->getMeetingInfo($getMeetingInfoParams);
        if ($response->getReturnCode() == 'FAILED') {

            $this->logoutUrl = url($this->logoutUrl).'/'.$room->url;
            $this->meetingsParams = [
                'meetingUrl' =>  $room->url,
                'meetingName' => $room->name,
                'attendeePassword' => decrypt($room->attendee_password),
                'moderatorPassword' => $user->password,
                'welcome_message' =>$room->welcome_message,
                'setRecord' =>$room->meeting_record,
                'logoutUrl' => $this->logoutUrl,
                'username' => $user->name


            ];
            return $this->createMeeting();

        } else {

            $joinMeetingParams = [

                'meetingId'  =>  decrypt($request->input('room')),
                'username'   =>  $user->name,
                'password'   =>  $user->password
            ];

            $url = Helper::joinMeeting($joinMeetingParams);
            return redirect()->to($url);

        }
    }

    public function inviteAttendee()
    {
        $pageName ='Invited Meetings';
        $user = User::findOrFail(Auth::id());
        $currentDate  = Carbon::now(Helper::get_local_time())->format('yy-m-d H:i');

        $roomList = $user->attendees()
            ->whereHas('rooms')
            ->with('rooms')
            ->get()
            ->pluck('rooms')
            ->collapse()
            ->where('end_date' ,'>=',$currentDate)
            ->sort();

        $roomList = Helper::paginate($roomList,10,null,[
            'path' =>'invite-meetings'
        ]);

        return view('public.rooms.auth.invitedMeetings',compact('pageName','roomList'));

    }

    public function inviteParticipant($url)
    {
        $pageName = "Invite Participants";
        $meeting = Room::where('url',$url)
            ->firstOrFail();


        $attendees = $meeting->attendees()
            ->paginate(10);

        $files  =  Files::paginate(2);

        return view('public.rooms.auth.addParticipant',compact('pageName','meeting','attendees','files'));
    }

    public function roomAttendees(Request $request)
    {
        if(empty($request->emails))
        {
            return response()->json(['result' => ['error'=>'Please Enter Atleast One Email']]);
        }
        $validEmails=[];
        $authUsers = [];
        $notAuthUser = [];
        $room = Room::where('url',$request->room)->firstOrFail();
        foreach ($request->emails as $email)
        {
            if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
                /**
                 * Valid Emails
                 */
                $validEmails[] = $email;
            }
        }
        foreach ($validEmails as $email)
        {
            $user = User::where('email',$email)->first();
            if (!empty($user))
            {
                $authUsers[] = $user;

            }else{

                $notAuthUser[] = $email;
            }
        }
        foreach ($authUsers as $user)
        {
            $attendee = Attendee::create(['email'=>$user->email,'user_id'=>$user->id]);
            $attendee->rooms()->attach($room->id);
        }
        $when = now()->addSeconds(5);
        foreach ($notAuthUser as $userEmail)
        {
            $user = User::findOrFail(Auth::id());
            Notification::route('mail',$userEmail)
                ->notify((new InviteParticipantMail(
                   [
                        'toEmail' => encrypt($userEmail),
                        'from' => $user,
                        'meetingName'=>  $room->name,
                        'meeting_id' => encrypt($room->id)
                   ]
                ))->delay($when));
        }

        return response()->json(['result'=>['success'=>200]]);


    }

    public function destroy (Room $room)
    {

        $room->delete();
        return redirect()->back()->with(['success'=>'Room Deleted Successfully !!']);
    }


}
