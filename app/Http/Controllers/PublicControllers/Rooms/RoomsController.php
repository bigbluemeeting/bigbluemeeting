<?php

namespace App\Http\Controllers\PublicControllers\Rooms;

use App\Attendee;
use App\EmailTemplate;
use App\Helpers\bbbHelpers;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\MeetingsRequest;

use App\Files;
use App\Notifications\AddParticipantMail;
use App\Notifications\InviteParticipantMail;
use App\Room;
use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\File;

class RoomsController extends Controller
{
    protected $logoutUrl = '/meetings/';
    protected $meetingsParams = [];

    public function __construct()
    {

        $this->middleware('auth', ['only' => [
            'update','index','edit','destroy','store'
        ]]);
    }
    public function index()
    {
        try {
            $credentials = bbbHelpers::setCredentials();
            if (!$credentials)
            {
                return redirect(URL::to('settings'))->with(['danger'=>'Please Enter Settings']);
            }
            $pageName = 'Meetings List';


            return view('public.rooms.index', compact('pageName'));


        } catch (\Exception $exception) {
            return view('errors.500')->with(['danger' => $exception->getMessage()]);
        }
    }

    /**
     * Upcoming Meetings
     */

    public function upComingMeetings()
    {
        try{

            $user = User::findOrFail(Auth::id());
            $currentDate = Carbon::now(Helper::get_user_local_timezone())->toDateTimeString();
            $upComingMeetings = $user->rooms()
                ->where('end_date', '>=', $currentDate)
                ->orderBy('id', 'DESC')
                ->paginate(10);

            return \request()->json(200,$upComingMeetings);


        }catch (\Exception $exception)
        {

        }
    }

    /**
     * Past Meetings
     */

    public function pastMeetings()
    {
        try{
            $user = User::findOrFail(Auth::id());
            $currentDate = Carbon::now(Helper::get_user_local_timezone())->toDateTimeString();

            $pastMeetings = $user->rooms()
                ->where('end_date', '<', $currentDate)
                ->orderBy('id', 'DESC')
                ->paginate(10);
            return \request()->json(200,$pastMeetings);
        }catch (\Exception $exception)
        {

        }
    }

    public function store(MeetingsRequest $request)
    {
        try{
            return $this->saveRoomToDb($request);
        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }
    }

    public function edit($room)
    {
        try{
            $room = Room::findOrFail($room);

            return response()->json(['result' =>$room]);
        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }
    }

    public function update(MeetingsRequest $request,$url)
    {
        try{
          $room = Room::find($url);
          $this->updateRooms($request,$room);
          return  $this->upComingMeetings();

        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }
    }

    public function saveRoomToDb($request)
    {
        try{
            $startDate = Carbon::createFromFormat('yy-m-d',$request->input('start_date'))->toDateString();
            $startTime =Carbon::parse($request->input('startTime'))->format('H:i');
            $start_date = $startDate.' '.$startTime;
            $endDate =  Carbon::createFromFormat('yy-m-d',$request->input('end_date'))->toDateString();
            $endTime =  Carbon::parse($request->input('endTime'))->format('H:i');
            $end_date = $endDate.' '.$endTime;
            $data = $request->except('startTime','endTime');
            $data['user_id'] = Auth::id();
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
            $data['attendee_password'] = encrypt(Auth::id().Str::random(2).'attendeePassword');

            $room = Room::create($data);
            $user = User::findOrFail(Auth::id());
            $room->url =strtolower($user->name).'-'.Str::random(3).'-'.$room->id.Str::random(2);
            $room->save();
            return $this->upComingMeetings();

        }catch (\Exception $exception)

        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }
    }

    public function updateRooms($request,$room)
    {
        try{
            $startDate = Carbon::createFromFormat('yy-m-d',$request->input('start_date'))->toDateString();
            $startTime =Carbon::parse($request->input('startTime'))->format('H:i');
            $start_date = $startDate.' '.$startTime;
            $endDate =  Carbon::createFromFormat('yy-m-d',$request->input('end_date'))->toDateString();
            $endTime =  Carbon::parse($request->input('endTime'))->format('H:i');
            $end_date = $endDate.' '.$endTime;
            $data= $request->except('startTime','endTime','commit','_method','_token');
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
            $request->has('mute_on_join') ? $data['mute_on_join'] : $data['mute_on_join'] = 0;

            $room->update($data);
            return $room;
        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }
    }

    public function createMeeting($name=null)
    {
        try{
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

                $apiUrl =bbbHelpers::joinMeeting($joinMeetingParams);
                return redirect()->to($apiUrl);
            }


        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }
    }

    public function show($url)
    {
        try{
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
                }
            }else{

                $roomsRecordingList = bbbHelpers::recordingLists($url);
                $pageName = ucwords($room->user->username);
                return view('public.rooms.notauth.join',compact('pageName','room','roomsRecordingList'));
            }
        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }

    }

    public function join(Request $request)
    {

        try{

            $room = Room::where('url',decrypt($request->input('room')))->firstOrFail();
            $credentials = bbbHelpers::setCredentials();
            if (!$credentials)
            {
                return redirect(URL::to('settings'))->with(['danger'=>'Please Enter Settings']);
            }

            $user = User::findOrFail(Auth::id());
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

            $files = $user
                ->rooms()
                ->where('url',$room->url)
                ->whereHas('files')
                ->with('files')
                ->get()
                ->pluck('files')
                ->collapse();

            if (count($files) < 1)
            {
                $files = $user->files()
                    ->where('setDefault',1)
                    ->get();
            }

            if (count($files) > 0)
            {
                foreach ($files as $file)
                {
                    $this->meetingsParams['files'][] =$file->name;
                }
            }else{

                $this->meetingsParams['files'] =[];
            }

            return $this->createMeeting();
        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }
    }

    public function inviteAttendee()
    {
        try{
            $pageName = __('Invited Meetings');
            $user = User::findOrFail(Auth::id());
            $currentDate  = Carbon::now(Helper::get_user_local_timezone())->toDateTimeString();

            $roomList = $user->attendees()
                ->whereHas('rooms')
                ->with('rooms')
                ->get()
                ->pluck('rooms')
                ->collapse()
                ->where('end_date' ,'>=',$currentDate)
                ->count();

            return view('public.rooms.auth.invitedMeetings',compact('pageName','roomList'));

        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }

    }

    public function getInvitedMeetings()

    {
        try{
            $user = User::findOrFail(Auth::id());
            $currentDate  = Carbon::now(Helper::get_user_local_timezone())->toDateTimeString();

            $roomList = $user->attendees()
                ->whereHas('rooms')
                ->with('rooms')
                ->get()
                ->pluck('rooms')
                ->collapse()
                ->where('end_date' ,'>=',$currentDate)
                ->sort();
            $roomList = Helper::paginate($roomList,1,null,[
                'path' =>'invite-meetings'
            ]);
            return \request()->json(200,$roomList);
        }catch (\Exception $exception)
        {

        }

    }
    public function showDetails($url)
    {
        try{
            $currentDate = Carbon::now(Helper::get_user_local_timezone())->toDateTimeString();

            $pageName = __("Invite Participants");
            $meeting = Room::where('url',$url)
                ->firstOrFail();
            $pastMeeting = false;
            if ($meeting->end_date < $currentDate)
            {
                $pastMeeting = true;
            }

            $attendees = $meeting->attendees()
                ->latest()
                ->paginate(10);

            $files = $meeting->files()->paginate(20);

            return view('public.rooms.auth.addParticipant',compact('pageName','url','meeting','pastMeeting','attendees','files'));

        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @return  Meeting Details And Participants Information
     */
    public function addParticipantDetails($url)
    {
        try{

            $currentDate = Carbon::now(Helper::get_user_local_timezone())->toDateTimeString();

            $meeting = Room::where('url',$url)
                ->firstOrFail();
            $pastMeeting = false;
            if ($meeting->end_date < $currentDate)
            {
                $pastMeeting = true;
            }

            $attendees = $meeting->attendees()
                ->latest()
                ->paginate(10);

            return \request()->json(200,[
                'meeting'=>$meeting,
                'pastMeeting'=>$pastMeeting,
                'attendees'=>$attendees,
            ]);

        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @return  meetings files
     */
    public function meetingFiles($url)
    {
        try{

            $meeting = Room::where('url',$url)
                ->firstOrFail();

            if($meeting){
                $files = $meeting->files()->paginate(20);

                return \request()->json(200,[
                    'files'=>$files
                ]);
            }else{
                throw new \Exception('Meeting not found.');
            }

        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }


    }

    public function roomAttendees(Request $request)
    {

        try{

            if(empty($request->emails))
            {
                return response()->json(['error' => __('Please enter at least one e-mail address.')], 401);
            }

            $validEmails=[];
            $sendEmails=[];
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

                $attendeeCount=Attendee::with('rooms')
                    ->where('email',$email)
                    ->get()
                    ->pluck('rooms')->collapse()
                    ->where('url',$request->room)
                    ->count();
                $sendEmails[] = $email;


                if (!$attendeeCount > 0)
                {
                    $attendee = Attendee::create(['email'=>!empty($user) ? $user->email : $email,'user_id'=>!empty($user) ? $user->id :'0']);
                    $attendee->rooms()->attach($room->id);
                }
            }

            $emailTem = EmailTemplate::whereUserId(\auth()->id())->first();

            if (!$emailTem)
            {
                $emailTem = EmailTemplate::first();
            }

            $url =url('/').'/meetings/'.$room->url;
            $user = \auth()->user();
            $header = nl2br(str_replace([
                '[meeting:name]',
                '[user:email]',
                '[meeting:url]',
                '[meeting:start]',
                '[meeting:end]'],

                [
                    $room->name,
                    '<a href="">'.$user->email. '</a>',
                    '<div style="text-align: center; margin-top: 20px; "><a href='.$url.'  style="color: #FFFFFF; font-size: 20px; background-color: #83C36D; border-radius: 4px; border: 8px solid #83C36D;"> Join Meeting Here</a></div>',
                    \Carbon\Carbon::parse($room->start_date)->format(' D M d  g:i A yy'),
                    \Carbon\Carbon::parse($room->end_date)->format(' D M d g:i A yy')
                ],
            $emailTem['invite_participants']));

            $footer =  nl2br($emailTem['mail_footer']);


            $mailSubject = str_replace(['[meeting:name]','[user:email]'],
                [$room->name,$user->email],
                $emailTem['mail_subject']);

            $mailParams = [
                'from'    =>  $emailTem['mail_from_name'],
                'header'  =>  $header,
                'subject' =>  $mailSubject,
                'footer'  =>  $footer
            ];

            $when = now()->addSeconds(5);
            $modMailParams = [

                'modMailHeader' =>nl2br(str_replace('[site:url]','<a href="'.\url('/').'">'.url('/').'</a>',$emailTem['mod_mail'])),
                'modMailFooter' =>nl2br(str_replace(['[subscribe:link]','[unsubscribe:link]'],
                    [
                        '<a href="'.route('subscribe',\auth()->user()->email).'">'.route('subscribe',\auth()->user()->email). '</a>',
                        '<a href="'.route('unsubscribe',\auth()->user()->email).'">'.route('unsubscribe',\auth()->user()->email). '</a>'
                    ],
                    $emailTem['mod_mail_footer']))
            ];

            foreach ($sendEmails as $userEmail) {

                if (\auth()->user()->send_email)
                {
                    Notification::route('mail',\auth()->user()->email)
                        ->notify((new AddParticipantMail(
                            [
                                'mailParams' => $modMailParams,
                                'meeting' => $room
                            ]
                        ))
                            ->delay($when));
                }

                Notification::route('mail',$userEmail)
                    ->notify((new InviteParticipantMail(
                        [
                            'toEmail'    => encrypt($userEmail),
                            'mailParams' => $mailParams,
                            'meeting_id' => encrypt($room->id),
                            'meeting' => $room,
                        ]
                    ))->delay($when));
            }

            return $this->addParticipantDetails($request->room);

        }catch (\Exception $exception)
        {
            return response()->json(['danger'=>$exception->getMessage()]);
        }
    }


    public  function deleteFile(Request $request)
    {
        try{

            $file= Files::findOrFail($request->id);
            if($file){
                $filename  = public_path().Files::Folder.$file->name;
                File::delete($filename);
                $file->delete(); // delete db record
                return $this->meetingFiles($request->url);
            }else{
                // return empty array
                return \request()->json(200,[
                    'files'=> []
                ]);
            }

        }catch (\Exception $exception)
        {
            return response()->json(['error'=>$exception->getMessage()]);
        }
    }
    public function destroy (Request $request)
    {
        try{

            $room = Room::findOrFail($request->id);
            $room->delete();
            if ($request->type)
            {

                return $this->upComingMeetings();

            }else{

                return $this->pastMeetings();
            }

        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }
    }

    /**
     * Delete attendees From Meetings
     */

    public function deleteAttendee(Request $request)
    {
        try{

            $attendee = Attendee::findOrFail($request->id);

            $attendee->delete();
            return $this->addParticipantDetails($request->url);

            return redirect()->back()->with(['success'=>'Participant deleted from this meeting']);
        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }


    }


}
