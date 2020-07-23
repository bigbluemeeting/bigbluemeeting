<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\bbbHelpers;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;

use App\Room;
use App\User;
use BigBlueButton\Parameters\DeleteRecordingsParameters;
use BigBlueButton\Parameters\PublishRecordingsParameters;
use Illuminate\Auth\Access\Gate;
use Illuminate\Http\Request;
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\GetRecordingsParameters;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use SimpleXMLElement;
use Spatie\Permission\Models\Role;

class RecordingsController extends Controller
{
    protected $recordingList = [];
    protected $meetingsRecordings = [];
    protected $meetingsParams = [];
    public function __construct()
    {
        $this->middleware('auth');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        try{
            $pageName ="Recordings List";
            $rooms = Auth::user()
                ->rooms()
                ->where('meeting_record',1)
                ->get();


//        dd($rooms);
            $meetings = Auth::user()
                ->meetings()
                ->get();

            foreach ($rooms as $room)
            {
                $this->meetingsParams = [
                    'url' => $room->url,
                    'rooms' => true,
                ];

                $this->recordings();
            }


            foreach ($meetings as $meeting)
            {
                $this->meetingsParams = [
                    'url' => $meeting->url,
                    'rooms' => false,
                ];

                $this->recordings();
            }



            $this->recordingList = Helper::paginate(
                $this->recordingList,
                10,
                null,
                [
                    'path' =>'recordings'
                ]);



            $this->meetingsRecordings = Helper::paginate(
                $this->meetingsRecordings,
                10,
                null,
                [
                    'path' =>'recordings'
                ]
            );

            return view('admin.recording.index')->with(
                [
                    'pageName'=>$pageName,
                    'recordingList'=>$this->recordingList,
                    'meetingRecordings' => $this->meetingsRecordings,
                ]);
        }catch (\Exception $exception)
        {
            return view('errors.500')->with(['danger'=>$exception->getMessage()]);
        }


    }

    /**
     * @param $meetingId
     * Set Rooms & Meetings Recoding List
     */

    /**
     * Cache Function
     */
    public function cache()
    {


//        dd($this->recordingList);
//        dd(Cache::pull('posts.count'));
//        $rooms = Cache::remember(
//            'posts.count',
//            now()->addMinute()
//            ,function () {
//            $rooms = Auth::user()
//                ->rooms()
//                ->where('meeting_record',1)
//                ->get();
//            foreach ($rooms as $room)
//            {
//
//                $recordingParams = new GetRecordingsParameters();
//                $recordingParams->setMeetingId($room->url);
//                $bbb = new BigBlueButton();
//                $response = $bbb->getRecordings($recordingParams);
//                if ($response->getMessageKey() == null) {
//
//                    foreach ($response->getRawXml()->recordings->recording as $recording) {
//                        $this->recordingList[] = json_encode($recording) ;
//
//                }
//                }
//            }
//                return $this->recordingList;
//            });


//        $this->recordingList = [];
//        foreach ($rooms as $room)
//        {
//
//            $this->recordingList[] = json_decode($room);
//        }
//        dd($this->recordingList);
//        dd(Cache::pull('posts.count'));
//        dd(json_decode($rooms));
//       $this->recordingList = json_decode($rooms);

    }
    public function recordings()
    {



        try{
            $recordingParams = new GetRecordingsParameters();
            $recordingParams->setMeetingId($this->meetingsParams['url']);
            $credentials = bbbHelpers::setCredentials();
            if (!$credentials)
            {
                return redirect(\Illuminate\Support\Facades\URL::to('settings'))->with(['danger'=>'Please Enter Settings']);
            }
            $bbb = new BigBlueButton($credentials['base_url'],$credentials['secret']);
            $response = $bbb->getRecordings($recordingParams);


            if ($response->getMessageKey() == null) {

                foreach ($response->getRawXml()->recordings->recording as $recording) {

                    $this->meetingsParams['rooms'] ? $this->recordingList[] = $recording : $this->meetingsRecordings[] = $recording ;

                }
            }
        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }

    }


    /**
     * @param $id
     * Published Or Unpublished Recordings
     */
    public function publishedRecording(Request $request)
    {
        try{
            $credentials = bbbHelpers::setCredentials();
            if (!$credentials)
            {
                return redirect(\Illuminate\Support\Facades\URL::to('settings'))->with(['danger'=>'Please Enter Settings']);
            }
            $bbb = new BigBlueButton($credentials['base_url'],$credentials['secret']);

            $request->published ? $publish = true : $publish = false;
            $publishRecording  = new PublishRecordingsParameters($request->recording,$publish);
            $response = $bbb->publishRecordings($publishRecording);
            return redirect()->back()->with(['success'=>'Meeting Status Changed']);
        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

        try{
            $credentials = bbbHelpers::setCredentials();
            if (!$credentials)
            {
                return redirect(\Illuminate\Support\Facades\URL::to('settings'))->with(['danger'=>'Please Enter Settings']);
            }
            $bbb = new BigBlueButton($credentials['base_url'],$credentials['secret']);
            $deleteRecordingsParams= new DeleteRecordingsParameters($id); // get from "Get Recordings"
            $response = $bbb->deleteRecordings($deleteRecordingsParams);
            if ($response->getReturnCode() == 'SUCCESS') {
                return redirect()->back()->with(['success'=>'Recording Deleted Successfully']);
            } else {

                return redirect()->back()->with(['success'=>'Something Wrong Please Try Later']);
            }
        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }


    }

    public function invitedRoomsRecordings()
    {
        try{
            $rooms = Auth::user()->attendees()
                ->whereHas('rooms')
                ->with('rooms')
                ->get()
                ->pluck('rooms')
                ->collapse()
                ->where('meeting_record' ,1);

            foreach ($rooms as $room)
            {
                $this->recordings($this->meetingsParams = [
                    'url' => $room->url,
                    'rooms' => true,
                ]);
            }

            $recordingList = [];
            foreach ($this->recordingList as $recording)
            {
                if ($recording->published == 'true')
                {
                    $recordingList [] = $recording;
                }
            }
            $recordingList = Helper::paginate(
                $recordingList,
                10,
                null,
                [
                    'path' =>'invited-rooms-recordings'
                ]);



            $pageName = 'Meeting Recordings';
            return view('admin.recording.invited-rooms',with([

                'pageName' => $pageName,
                'recordingList' => $recordingList,
            ]));

        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }


    }
}
