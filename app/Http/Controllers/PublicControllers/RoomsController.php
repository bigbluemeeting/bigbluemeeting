<?php

namespace App\Http\Controllers\PublicControllers;

use App\Http\Controllers\Controller;
use App\Room;
use App\User;
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;

use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\IsMeetingRunningParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
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
        $pageName='Room List';


        $user = User::findOrFail(Auth::id());

        $roomList = $user->rooms()->paginate(10);


        return view('public.rooms.index',compact('pageName','roomList'));
    }

    public function store(Request $request)
    {
//
//        $startTime = Carbon::parse($request->input('startTime'));
//        dd($startTime);



        $startDate = Carbon::createFromFormat('yy-m-d',$request->input('startDate'))->toDateString();

        $startTime =Carbon::parse($request->input('startTime'))->format('H:i:s');
        dd($startTime);
        $request->validate([
            'name' => 'required|max:50',
            'maximum_people' => 'required|integer|min:0'
        ]);


        $data= $request->all();
        $data['user_id'] = Auth::id();

        $room = Room::create($data);

        $user = User::findOrFail(Auth::id());

        $room->url =strtolower($user->name).'-'.Str::random(3).'-'.$room->id.Str::random(2);

        $room->save();



        $attendeePassword =  'attendeePassword';

        $this->logoutUrl = url($this->logoutUrl).'/'.$room->url;

        $this->meetingsParams = [
            'meetingUrl' => $room->url,
            'meetingName' =>  $request->input('name'),
            'attendeePassword' => $attendeePassword,
            'moderatorPassword' => $user->password,
        ];

        return $this->createMeeting('create');
    }

    public function createMeeting($name=null)
    {

        $bbb = new BigBlueButton();
        $createMeetingParams = new CreateMeetingParameters($this->meetingsParams['meetingUrl'] , $this->meetingsParams['meetingName']);
        $createMeetingParams->setLogoutUrl($this->logoutUrl);
        $createMeetingParams->setRecord(true);
        $createMeetingParams->setAttendeePassword($this->meetingsParams['attendeePassword']);
        $createMeetingParams->setModeratorPassword($this->meetingsParams['moderatorPassword']);
        $response = $bbb->createMeeting($createMeetingParams);

        if ($response->getReturnCode() == 'FAILED') {
            return 'Can\'t create room! please contact our administrator.';

        } else {

            $getMeetingInfoParams = new GetMeetingInfoParameters($this->meetingsParams['meetingUrl'],$this->meetingsParams['moderatorPassword']);
            $response = $bbb->getMeetingInfo($getMeetingInfoParams);


            if ($name== 'create')
            {
                return $this->index();
            }
            else
            {

                $joinMeetingParams = new JoinMeetingParameters($this->meetingsParams['meetingUrl'], $this->meetingsParams['username'], $this->meetingsParams['moderatorPassword']);
                $joinMeetingParams->setRedirect(true);
                $apiUrl = $bbb->getJoinMeetingURL($joinMeetingParams);
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
            $attendeePassword =  'attendeePassword';
            $this->logoutUrl = url($this->logoutUrl);
            $this->meetingsParams = [
                'meetingUrl' => $room->url,
                'meetingName' => $room->name,
                'attendeePassword' =>$attendeePassword,
                'moderatorPassword' => $user->password,
                'username' => $user->name
            ];

            return $this->createMeeting();
        } else {

            $joinMeetingParams = new JoinMeetingParameters(decrypt($request->input('room')), $user->name, $user->password);
            $joinMeetingParams->setRedirect(true);
            $url = $bbb->getJoinMeetingURL($joinMeetingParams);
            echo "<script>window.open('".$url."', '_blank')</script>";
            return $this->index();
        }
   }
}
