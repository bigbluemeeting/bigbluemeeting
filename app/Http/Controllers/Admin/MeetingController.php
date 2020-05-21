<?php

namespace App\Http\Controllers\Admin;

use App\bigbluebutton\tests\TestCase;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Meeting;
use App\Room;
use App\User;
use BigBlueButton\BigBlueButton;
//use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use App\bigbluebutton\src\Parameters\CreateMeetingParameters;

class MeetingController extends Controller
{

    protected $meetingList = [];

    protected $logoutUrl = '/admin/meetings';
    protected $meetingsParams = [];
    protected $meetingUrl,$meetingName,$attendeePassword,$moderatorPassword;
    public function __construct()
    {
//        $this->middleware('auth');

    }

    /**
     * Meeting List
     */
    public function index()
    {

        $this->middleware('auth');
        $pageName ='Meetings List';
        $user = User::FindOrFail(Auth::id());
        $per = $user->getAllPermissions()->pluck('name')->toArray();


//        if (in_array('master_manage',$per))
//        {
//            $roomList = Room::paginate(10);
//            return view('admin.meetings.index',compact('roomList','pageName'));
//        }
//        if (in_array('users_manage',$per))
//        {
//            $roomList = Room::paginate(10);
//            return view('admin.meetings.index',compact('roomList','pageName'));
//        }
//        if (in_array('moderate',$per))
//        {
//            $roomList = $user->rooms()->paginate(10);
//            return view('admin.meetings.index',compact('roomList','pageName'));
//
//        }



        $roomList =$user->meetings()->paginate(10);
//         $roomList = $user->attendees()
//             ->whereHas('rooms')
//             ->with('rooms')
//             ->get()
//             ->pluck('rooms')
//             ->collapse();
//
//        $roomList = Helper::paginate($roomList,10,null,[
//           'path' =>'meetings'
//        ]);


        return view('admin.meetings.index',['roomList'=>$roomList,'pageName'=>$pageName]);



    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //


//        $pageName ="Add Meeting";
//
//        return view('admin.meetings.create',compact('pageName'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $this->middleware('auth');
        $request->validate([
            'name' => 'required|max:50',

        ]);

        $data = $request->except('access_code');


        if (!$request->has('mute_on_join'))
        {
            $data['mute_on_join'] = 0;
        }
        if (!$request->has('require_moderator_approval'))
        {
            $data['require_moderator_approval'] =0;
        }
        if (!$request->has('anyone_can_start'))
        {
            $data['anyone_can_start'] = 0;
        }
        if (!$request->has('all_join_moderator'))
        {
            $data['all_join_moderator'] =0;
        }
        if (!$request->has('auto_join'))
        {
            $data['auto_join'] =0;
        }

        $data['user_id'] = Auth::id();
        $data['attendee_password'] = encrypt(Auth::id().Str::random(2).'attendeePassword');

//        dd($data);
        $meeting = Meeting::create($data);
        $user = User::findOrFail(Auth::id());
        $meeting->url =strtolower($user->name).'-'.Str::random(4).'-'.$meeting->id.Str::random(2);
        $meeting->save();


        $this->logoutUrl = url($this->logoutUrl);


        $this->meetingsParams = [
            'meetingUrl' => $meeting->url,
            'meetingName' =>  $request->input('name'),
            'attendeePassword' => decrypt($data['attendee_password']),
            'moderatorPassword' => $user->password,
        ];

        return $this->createMeeting('create');


    }

   private function createMeeting($name=null)
   {
       $bbb = new BigBlueButton();
       $createMeetingParams = new CreateMeetingParameters($this->meetingsParams['meetingUrl'] , $this->meetingsParams['meetingName']);
       $createMeetingParams->setLogoutUrl($this->logoutUrl);
       $createMeetingParams->setRecord(true);
       $createMeetingParams->setAttendeePassword($this->meetingsParams['attendeePassword']);
       $createMeetingParams->setModeratorPassword($this->meetingsParams['moderatorPassword']);
       $createMeetingParams->setMuteOnStart(true);
       $createMeetingParams->setOpenJoin();
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($url)
    {
        //
        $meeting = Meeting::where('url',$url)->firstOrFail();



            $pageName = ucwords($meeting->user->username);
            $password = $meeting->user->password;
            $room = $meeting->url;
            return view('public.meetings.join',compact('pageName','password','room'));



    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function joinMeeting($url)
    {

        $meeting  = Meeting::where('url',$url)->firstOrFail();
        $bbb = new BigBlueButton();
        $user = User::findOrFail(Auth::id());
        $getMeetingInfoParams = new GetMeetingInfoParameters($url,$user->password);
        $response = $bbb->getMeetingInfo($getMeetingInfoParams);

//        dd($response);

//        dd($response);

        if ($response->getReturnCode() == 'FAILED') {

            $this->logoutUrl = url($this->logoutUrl);
            $this->meetingsParams = [
                'meetingUrl' => $meeting->url,
                'meetingName' =>  $meeting->name,
                'attendeePassword' => decrypt(decrypt($meeting->attendee_password)),
                'moderatorPassword' => $user->password,
                'username' => $user->name
            ];

            return $this->createMeeting();

        } else {



            $joinMeetingParams = new JoinMeetingParameters($url, $user->name, $user->password);
            $joinMeetingParams->setRedirect(true);
            $apiUrl = $bbb->getJoinMeetingURL($joinMeetingParams);
            return redirect()->to($apiUrl);
        }
    }

    public function meetingAttendees(Room $meeting)
    {
        $pageName  = 'Meeting Attendees';

        $attendees =  $meeting->attendees->unique('email')->values();
        $attendees = Helper::paginate($attendees,10,null,[
            'path' => ''
        ]);


        return view('admin.meetings.attendees',compact('pageName','attendees'));
    }




}
