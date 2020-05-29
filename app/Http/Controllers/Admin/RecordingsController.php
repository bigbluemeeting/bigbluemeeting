<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;

use App\User;
use Illuminate\Http\Request;
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\GetRecordingsParameters;
use Illuminate\Support\Facades\Auth;

class RecordingsController extends Controller
{
    protected $recordingList = [];
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
        $pageName ="Recordings List";

//        $rooms = Auth::user()
//            ->rooms()
//            ->where('meeting_record',1)
//            ->get();
//
//        foreach ($rooms as $room)
//        {
//            $recordingParams = new GetRecordingsParameters();
//            $recordingParams->setMeetingId($room->url);
//            $bbb = new BigBlueButton();
//            $response = $bbb->getRecordings($recordingParams);
//
//            if ($response->getReturnCode() == 'SUCCESS') {
////            dd($response->getRawXml());
//                foreach ($response->getRawXml()->recordings->recording as $recording) {
//                    // process all recording
//
//                    $this->recordingList[] =$recording;
//                }
//            }
//        }
//        dd($this->recordingList);
//

        $recordingParams = new GetRecordingsParameters();
//        $recordingParams->setMeetingId($room->url);
        $bbb = new BigBlueButton();
        $response = $bbb->getRecordings($recordingParams);

        if ($response->getReturnCode() == 'SUCCESS') {
//            dd($response->getRawXml());
            foreach ($response->getRawXml()->recordings->recording as $recording) {
                // process all recording

                $this->recordingList[] =$recording;
            }
        }

        $this->recordingList = Helper::paginate(

            $this->recordingList,
            10,
            null,
            [
                'path' =>'recordings'
            ]);

        return view('admin.recording.index')->with(['pageName'=>$pageName,'recordingList'=>$this->recordingList]);

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
        //
    }
}
