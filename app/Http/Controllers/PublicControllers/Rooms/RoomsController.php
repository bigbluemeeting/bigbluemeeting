<?php

namespace App\Http\Controllers\PublicControllers\Rooms;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Meeting;
use App\Room;
use App\User;
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;

use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\IsMeetingRunningParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RoomsController extends Controller
{
    //

    protected $logoutUrl = '/rooms/';
    protected $meetingsParams = [];

    public function index()
    {
        $pageName='Rooms List';
        $user = User::findOrFail(Auth::id());

        $currentDate  = Carbon::now(Helper::get_local_time())->format('yy-m-d H:i');

        $pastMeetings = $user->rooms()
            ->where('end_date','<',$currentDate)
            ->orderBy('id','DESC')
            ->paginate(10);

        $upComingMeetings = $user->rooms()
            ->where('end_date','>=',$currentDate)
            ->orderBy('id','DESC')
            ->paginate(10);

        return view('public.rooms.index',compact('pageName','upComingMeetings','pastMeetings'));
    }

    public function store(Request $request)
    {

        $startDate = Carbon::createFromFormat('yy-m-d',$request->input('start_date'))->toDateString();
        $startTime =Carbon::parse($request->input('startTime'))->format('H:i');
        $start_date = $startDate.' '.$startTime;
        $endDate =  Carbon::createFromFormat('yy-m-d',$request->input('end_date'))->toDateString();
        $endTime =  Carbon::parse($request->input('endTime'))->format('H:i');
        $end_date = $endDate.' '.$endTime;

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


        return $this->createMeeting('create');
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

    public function show($room)
    {


        $room = Room::where('url',$room)->firstOrFail();
        if (Auth::check())
        {
            $pageName = ucwords($room->name);
            return view('public.rooms.auth.single',compact('pageName','room'));
        }else{

            $pageName = ucwords($room->user->username);
            $password = $room->user->password;
            $room = $room->url;
            return view('public.rooms.notauth.join',compact('pageName','password','room'));
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


        return view('public.rooms.auth.addParticipant',compact('pageName','meeting','attendees'));
    }

    public function roomAttendees(Request $request)
    {
        dd($request->emails);

    }


}
