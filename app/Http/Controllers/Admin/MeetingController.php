<?php

namespace App\Http\Controllers\Admin;


use App\bigbluebutton\tests\TestCase;
use App\Helpers\bbbHelpers;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Meeting;
use App\Room;
use App\User;
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\EndMeetingParameters;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use Bkwld\Croppa\URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;


class MeetingController extends Controller
{

    protected $meetingList = [];
    protected $checkCode = true;
    protected $data = [];

    protected $logoutUrl = '/rooms';
    protected $meetingsParams = [];
    protected $meetingUrl,$meetingName,$attendeePassword,$moderatorPassword;
    protected $autoJoin;

    public function __construct()
    {

        $this->middleware('auth', ['only' => [
            'update','index','edit','destroy','store'
        ]]);
    }

    /**
     * Meeting List
     */
    public function index()
    {

        try{
            $pageName ='Rooms List';

            $user = User::FindOrFail(Auth::id());

            $roomList =$user->meetings()
                ->orderBy('id','DESC')
                ->paginate(10);



            return view('admin.meetings.index',['roomList'=>$roomList,'pageName'=>$pageName]);


        }catch (\Exception $exception)
        {
            return view('errors.500')->with(['danger'=>$exception->getMessage()]);
        }



    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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


        try{
            $this->middleware('auth');
            $request->validate([
                'name' => 'required|max:50',


            ]);

            $credentials = bbbHelpers::setCredentials();
            if (!$credentials)
            {
                return redirect(\Illuminate\Support\Facades\URL::to('settings'))->with(['danger'=>'Please Enter Settings']);
            }

            $bbb = new BigBlueButton($credentials['base_url'],$credentials['secret']);

            $response = $bbb->getMeetings();

            $this->data = $request->all();
            $this->setDefaultData($request);
            $this->data['user_id'] = Auth::id();
            $this->data['attendee_password'] = encrypt(Auth::id().Str::random(2).'attendeePassword');
            $meeting = Meeting::create($this->data);
            $user = User::findOrFail(Auth::id());
            $meeting->url =strtolower($user->name).'-'.Str::random(4).'-'.$meeting->id.Str::random(2);
            $meeting->save();

            $this->logoutUrl = url($this->logoutUrl.'/'.$meeting->url);

            $this->meetingsParams = [
                'meetingUrl' => $meeting->url,
                'meetingName' =>  $request->input('name'),
                'attendeePassword' => decrypt($this->data['attendee_password']),
                'moderatorPassword' => $user->password,
                'muteAllUser' => $this->data['mute_on_join'] ? true :false,
                'moderator_approval' =>$this->data['require_moderator_approval'] ? true :false,
                'logoutUrl' => $this->logoutUrl,
                'setRecord' =>true,
                'username' => $user->username,

            ];

            $this->autoJoin = $this->data['auto_join'];
            return $this->createMeeting('create');
        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }



    }

    private function setDefaultData($request)
    {

        $request->has('mute_on_join') ? $this->data['mute_on_join'] :$this->data['mute_on_join'] = 0;
        $request->has('require_moderator_approval') ? $this->data['require_moderator_approval'] :$this->data['require_moderator_approval'] =0 ;
        $request->has('anyone_can_start') ? $this->data['anyone_can_start']: $this->data['anyone_can_start'] =0;
        $request->has('all_join_moderator') ? $this->data['all_join_moderator'] :$this->data['all_join_moderator'] =0 ;
        $request->has('auto_join') ? $this->data['auto_join'] : $this->data['auto_join'] = 0;
    }

    private function createMeeting($name=null)
    {

        try
        {
              bbbHelpers::setMeetingParams($this->meetingsParams);
            $response = bbbHelpers::createMeeting();
            if ($response->getReturnCode() == 'FAILED') {
                return 'Can\'t create room! please contact our administrator.';
            } else {

                $joinMeetingParams = [

                    'meetingId'  => $this->meetingsParams['meetingUrl'],
                    'username'   => $this->meetingsParams['username'],
                    'password'   => $this->meetingsParams['moderatorPassword']
                ];

                if ($name== 'create')
                {
                    if ($this->autoJoin)
                    {
                        $url = bbbHelpers::joinMeeting($joinMeetingParams);
                        return redirect()->to($url);
                    }
                    return $this->index();
                }
                else
                {
                    $url = bbbHelpers::joinMeeting($joinMeetingParams);
                    return redirect()->to($url);
                }

            }
        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
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


        try{
            $room = Meeting::where('url',$url)->firstOrFail();

            $pageName = ucwords($room->user->username);
            $response = Gate::allows('view',$room);
            if (!$response)
            {
                if (!empty($room->access_code))
                {
                    return view('public.meetings.access_code',compact('pageName','room'));
                }
                else{

                    $recordingList = bbbHelpers::recordingLists($url);
                    return view('public.meetings.join',compact('pageName','room','recordingList'));
                }
            }
            else{
                return redirect()->to(route('rooms.index'));
            }
        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }


    }

    public  function showDetails($url)
    {
        try{
            $pageName = "Meeting Details";
            $meeting = Meeting::where('url',$url)
                ->firstOrFail();

            $files = $meeting->files()->paginate(10);

            return view('admin.meetings.single',compact('pageName','meeting','files'));

        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }

    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($meeting)
    {

        try{
            $meeting = Meeting::findOrFail($meeting);
            return view('admin.meetings.editMeetingModal',compact('meeting'));
        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $meeting)
    {
        //

        try{
            $request->validate([
            'name' => 'required|max:50',
                ]);

            $credentials = bbbHelpers::setCredentials();
            if (!$credentials)
            {
                return redirect(\Illuminate\Support\Facades\URL::to('settings'))->with(['danger'=>'Please Enter Settings']);
            }

            $bbb = new BigBlueButton($credentials['base_url'],$credentials['secret']);

            $response = $bbb->getMeetings();

            $meeting = Meeting::findOrFail($meeting);
            $this->data = $request->all();
            $this->setDefaultData($request);
            $this->updateMeeting($meeting);

            return redirect()->back()->with(['success'=>'Rooms Updated Successfully']);

        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }





    }
    private function updateMeeting($meeting)
    {
        try{
            $meeting->update($this->data);
            $user = Auth::user();
            $this->logoutUrl = url($this->logoutUrl.'/'.$meeting->url);
            $this->meetingsParams = [
                'meetingUrl' => $meeting->url,
                'meetingName' =>  $this->data['name'],
                'attendeePassword' => decrypt($meeting['attendee_password']),
                'moderatorPassword' => $user->password,
//            $this->data['mute_on_join'] ? true :false,
                'muteAllUser' => true,
                'moderator_approval' =>$this->data['require_moderator_approval'] ? true :false,
                'logoutUrl' => $this->logoutUrl,
                'setRecord' =>true,
                'username' => $user->username,

            ];
            return $this->createMeeting('create');
//            $credentials = bbbHelpers::setCredentials();
//            if (!$credentials)
//            {
//                return redirect(\Illuminate\Support\Facades\URL::to('settings'))->with(['danger'=>'Please Enter Settings']);
//            }
//            $bbb = new BigBlueButton($credentials['base_url'],$credentials['secret']);

//            $getMeetingInfoParams = new GetMeetingInfoParameters($meeting->url,$user->password);
//            $response = $bbb->getMeetingInfo($getMeetingInfoParams);
//
//            if ($response->getReturnCode() == 'FAILED')
//            {
//
//                return $this->createMeeting('create');
//            }else{
//
//                $endMeetingParams = new EndMeetingParameters($meeting->url,$user->password);
//                $response = $bbb->endMeeting($endMeetingParams);
//                return $this->createMeeting('create');
//            }
        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($meeting)
    {

        try{
            $meeting = Meeting::findOrFail($meeting);
            $meeting->delete();
            return redirect()->back()->with(['success'=>'Rooms Deleted Successfully !!']);
        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }

    }

    public function joinMeeting($url)
    {



        try{
            $meeting  = Meeting::where('url',$url)->firstOrFail();
            $credentials = bbbHelpers::setCredentials();

            if (!$credentials)
            {
                return redirect(\Illuminate\Support\Facades\URL::to('settings'))->with(['danger'=>'Please Enter Settings']);
            }
            $bbb = new BigBlueButton($credentials['base_url'],$credentials['secret']);
            $user = User::findOrFail(Auth::id());
            $getMeetingInfoParams = new GetMeetingInfoParameters($url,$user->password);
            $response = $bbb->getMeetingInfo($getMeetingInfoParams);
            $this->logoutUrl = url($this->logoutUrl.'/'.$meeting->url);
            $this->meetingsParams = [
                'meetingUrl' => $meeting->url,
                'meetingName' =>  $meeting->name,
                'attendeePassword' => decrypt($meeting->attendee_password),
                'moderatorPassword' => $user->password,
                'username' => $user->name,
                'muteAllUser' => $meeting->mute_on_join,
                'moderator_approval' =>$meeting->require_moderator_approval,
                'logoutUrl' => $this->logoutUrl,
                'setRecord' => true,
            ];
            return $this->createMeeting();
//            $files =  Auth::user()
//                ->meetings()
//                ->where('url',$meeting->url)
//                ->whereHas('files')
//                ->with('files')
//                ->get()
//                ->pluck('files')
//                ->collapse();
//
//            if (count($files) < 1)
//            {
//                $files = $user->files()
//                    ->where('setDefault',1)
//                    ->get();
//            }
//            if (count($files) > 0)
//            {
//                foreach ($files as $file)
//                {
//                    $this->meetingsParams['files'][] =$file->name;
//
//                }
//            }
//
//            else{
//
//                $this->meetingsParams['files'] =[];
//            }
//
//            if ($response->getReturnCode() == 'FAILED') {
//
//                return $this->createMeeting();
//
//            } else {
//
//                $endMeetingParams = new EndMeetingParameters($meeting->url,$user->password);
//                $endMeetingResponse = $bbb->endMeeting($endMeetingParams);
//
//                return $this->createMeeting();
//
//
//            }

        }catch (\Exception $exception){

            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
//           dd($exception);
        }



    }





}
