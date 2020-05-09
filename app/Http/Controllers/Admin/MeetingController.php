<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Meeting;
use App\User;
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MeetingController extends Controller
{
    protected $meetingList = [];
    public function __construct()
    {
        $this->middleware('auth');

    }

    /**
     * Meeting List
     */
    public function index()
    {



        $pageName ='Rooms List';

        $user =User::FindOrFail(Auth::id());
        $per = $user->getAllPermissions()->pluck('name')->toArray();

        if (in_array('master_manage',$per))
        {
            $roomList = Meeting::paginate(10);
            return view('admin.meetings.index',compact('roomList','pageName'));
        }
        if (in_array('users_manage',$per))
        {
            $roomList = Meeting::paginate(10);
            return view('admin.meetings.index',compact('roomList','pageName'));
        }
        if (in_array('moderate',$per))
        {
            $roomList = $user->meetings()->paginate(10);
            return view('admin.meetings.index',compact('roomList','pageName'));

        }

         $roomList = $user->attendees()
             ->whereHas('meetings')
             ->with('meetings')
             ->get()
             ->pluck('meetings')
             ->collapse();

        $roomList = Helper::paginate($roomList,10,null,[
           'path' =>'meetings'
        ]);




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

        $request->validate([
            'name' => 'required|max:50',

        ]);

        $data = $request->all();


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


        $meeting = Meeting::create($data);

        $msg = ['success'=>'Meeting created successfully'];
        return $this->index();
//        return redirect()->route('admin::meetings.index')->with(['success' => 'Meeting created successfully']);
//

//        $data['user_id'] = Auth::id();
//        $meeting = Meeting::create($data);
//        $meeting_id = $meeting->id;
//
//        $bbb = new BigBlueButton();
//
//        $createMeetingParams = new CreateMeetingParameters($meeting_id, $request->input('name'));
//        $createMeetingParams->setAttendeePassword($request->input('attendee_password'));
//        $createMeetingParams->setModeratorPassword($request->input('moderator_password'));
//
//        $response = $bbb->createMeeting($createMeetingParams);
//        if ($response->getReturnCode() == 'FAILED') {
//            return 'Can\'t create room! please contact our administrator.';
//        } else {
//            // process after room created
//
//            return redirect()->route('admin::meetings.index')->with(['success' => 'Meeting created successfully']);
//
//        }






    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

    public function joinMeeting()
    {
        $bbb = new BigBlueButton();
        $joinMeetingParams = new JoinMeetingParameters('Demo Meeting', 'Demo Meeting', 'Demo Meeting');
        $joinMeetingParams->setRedirect(true);
        $url = $bbb->getJoinMeetingURL($joinMeetingParams);

            return redirect($url);
    }

    public function meetingAttendees(Meeting $meeting)
    {
        $pageName  = 'Meeting Attendees';

        $attendees =  $meeting->attendees->unique('email')->values();
        $attendees = Helper::paginate($attendees,10,null,[
            'path' => ''
        ]);


        return view('admin.meetings.attendees',compact('pageName','attendees'));
    }




}
