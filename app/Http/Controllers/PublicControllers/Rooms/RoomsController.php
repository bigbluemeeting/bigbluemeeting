<?php

namespace App\Http\Controllers\PublicControllers\Rooms;

use App\Attendee;
use App\EmailTemplate;
use App\Files;
use App\Helpers\bbbHelpers;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\MeetingsRequest;
use App\Mail\AttendeeMail;
use App\Meeting;
use App\Notifications\AddParticipantMail;
use App\Notifications\InviteParticipantMail;
use App\Room;
use App\User;
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\EndMeetingParameters;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use QCod\AppSettings\Setting\AppSettings;

class RoomsController extends Controller
{
    //


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
                return redirect(\Illuminate\Support\Facades\URL::to('settings'))->with(['danger'=>'Please Enter Settings']);
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
                $currentDate = Carbon::now(Helper::get_local_time())->format('yy-m-d H:i');
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
            $currentDate = Carbon::now(Helper::get_local_time())->format('yy-m-d H:i');

            $pastMeetings = $user->rooms()
                ->where('end_date', '<', $currentDate)
                ->orderBy('id', 'DESC')
                ->paginate(10);
//
            return \request()->json(200,$pastMeetings);


        }catch (\Exception $exception)
        {

        }
    }




    public function store(MeetingsRequest $request)
    {

        try{


            return $this->saveRoomToDb($request);

//            return $this->createMeeting('create');
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
//                return $this->inviteAttendee();
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
                return redirect(\Illuminate\Support\Facades\URL::to('settings'))->with(['danger'=>'Please Enter Settings']);
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

            $files =  Auth::user()
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
            }

            else{

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
            $pageName ='Invited Rooms';
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

        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }

    }

    public function showDetails($url)
    {
        try{
//            $user = User::findOrFail(Auth::id());
            $currentDate = Carbon::now(Helper::get_local_time())->format('yy-m-d H:i');
//
//            $pastMeetings = $user->rooms()
//                ->where('end_date', '<', $currentDate)
//                ->orderBy('id', 'DESC')
//                ->paginate(10);
//            dd($pastMeetings);
            $pageName = "Invite Participants";
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



            $files = $meeting->files()->paginate(10);

            return view('public.rooms.auth.addParticipant',compact('pageName','pastMeeting','meeting','attendees','files'));

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
                return response()->json(['result' => ['error'=>'Please Enter Atleast One Email']]);
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
//        $user = User::findOrFail(Auth::id());






            $modMailParams = [

                'modMailHeader' =>nl2br(str_replace('[site:url]','<a href="'.\url('/').'">'.url('/').'</a>',$emailTem['mod_mail'])),
                'modMailFooter' =>nl2br(str_replace(['[subscribe:link]','[unsubscribe:link]'],
                    [
//                    '.route('subscribe',\auth()->user()->email).'
//                '.route('unsubscribe',\auth()->user()->email).'
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
//
                    ))->delay($when));
            }



            return response()->json(['result'=>['success'=>200]]);
        }catch (\Exception $exception)
        {
            return redirect()->json(['danger'=>$exception->getMessage()]);
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

    public function deleteAttendee($id)
    {
        try{
            $attendee = Attendee::findOrFail($id);

            $attendee->delete();

            return redirect()->back()->with(['success'=>'Participant deleted from this meeting']);
        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }


    }


}
