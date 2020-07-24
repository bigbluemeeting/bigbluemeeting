<?php

namespace App\Http\Controllers\Admin;

use App\Files;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Meeting;
use App\Room;
use Carbon\Carbon;
use Croppa;

//use FileUpload;
use FileUpload\FileNameGenerator\Slug;
use FileUpload\FileUpload;
//use FileUpload\Validator\Simple;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FilesController extends Controller
{
    /*w*
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected  $filesDetails = [];

    protected $bbbPresentation = [];


    public function index()
    {
        // get all files

        try{
            $user = Auth::user();

            $files = $user->files()->orderBy('id','DESC')->paginate(10);
            $currentDate  = \Illuminate\Support\Carbon::now(Helper::get_local_time())->format('yy-m-d H:i');

            $rooms =  $user->rooms()
                ->where('end_date','>=',$currentDate)
                ->get();

            $meetings = $user->meetings;

            $pageName = 'File Upload';
            return view('admin.files.index',compact('files','pageName','meetings','rooms'));

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


        try
        {
            if ($request->has('rooms'))
            {
                $room = Room::findorFail($request->input('rooms'));
            }

            $file = $request->file('files');

            $path = public_path(Files::Folder);
            if(!File::exists($path)) {
                File::makeDirectory($path);
            };

            $size =$file[0]->getSize();
            $filename = $file[0]->getClientOriginalName();
            if (preg_match('/^.*?(?=\.)/',$filename,$match))
            {
                $actual_name = $match[0];
                $name = $match[0];
            }
            if (preg_match('/[^.]*$/',$filename,$match))
            {
                $extension =  '.'.$match[0];
            }

            $type = $file[0]->getClientMimeType();
            if (File::exists(public_path('uploads/'.$actual_name.$extension))) {
                $i = 1;
                while(file_exists('uploads/'.$actual_name.$extension))
                {

                    $actual_name = (string)$actual_name.'-'.$i;
                    $filename = $name.'-'.$i.$extension;
                    $i++;


                }
            }


            $url = Files::Folder . $filename;
            $validator = [
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/msword',
                'application/vnd.oasis.opendocument.text',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'application/vnd.oasis.opendocument.presentation',
                'application/vnd.ms-powerpoint',
                'application/pdf',
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/jpg',
                'text/plain'
            ];



            if(in_array($file[0]->getMimeType(),$validator)){

                if ($file[0]->getSize() <= 100000000)
                {
                    $file[0]->move($path,$filename);
                    $dataArray = [
                        'name' => $filename,
                        'type' => $type,
                        'size' => $size,
                        'user_id' => Auth::id(),
                        'upload_date' => Carbon::now(),

                    ];
                    $fileUploaded = Files::create($dataArray);
                    if ($request->has('rooms'))
                    {
                        $fileUploaded->rooms()->attach($room->id);
                    }
                    $data[] = [

                        'size' => Helper::formatBytes($size),
                        'id' => encrypt($fileUploaded->id),
                        'name' => $filename,
                        'url' => $url,
                        'type' =>$type,
                        'upload_date' => Carbon::now()->format('Y-m-d h:m A'),
                        'deleteType' => 'DELETE',
                        'setDefaultUrl' =>route('setDefault',$fileUploaded->id),
                        'deleteUrl' => route('files.destroy', $fileUploaded->id),
                    ];
                    return response()->json(['files' => $data]);
                }else{

                    $files[] = [
                        'name' => $file[0]->getClientOriginalName(),
                        'size' => '',
                        'error' =>'Your File Size is greater than 100MB'
                    ];

                }


            }else{

                $files[] = [
                    'name' => $file[0]->getClientOriginalName(),
                    'size' => '',
                    'error' =>'This File Type Not Allowed'
                ];
            }

            return response()->json(['files' => $files]);
        }catch (\Exception $exception)
        {
            return response()->json(['error'=>$exception->getMessage()]);
        }


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
    public function destroy(Files $file)
    {

        try{
            $filename  = public_path().Files::Folder.$file->name;
            File::delete($filename);
            $file->delete(); // delete db record
            return response()->json(['file'=>'Deleted']);
        }catch (\Exception $exception)
        {
            return response()->json(['error'=>$exception->getMessage()]);
        }

    }

    public function setDefault($id)
    {
        try{
            $user= Auth::user();
            $user->files()->where('setDefault',1)->update(['setDefault'=>0]);

            $files = Files::findOrFail($id);
            $files->setDefault = 1;
            $files->save();
            return redirect()->back();
        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }


    }

    public function addFileToRoom(Request $request)
    {


        try{
            $request->validate([
                'rooms' =>'required',
            ],[
                'rooms.required' =>'Please Select Room'
            ]);

            try{
                $decrypt['file'] = decrypt($request->input('file'));
                $decrypt['rooms'] = decrypt($request->input('rooms'));

            }catch (DecryptException $e)
            {

                return redirect()->back()->with(['danger'=>'Some Thing Wrong Please Try Later']);

            }

            $file = Files::findOrFail($decrypt['file']);

            $room = Room::findOrFail($decrypt['rooms']);

            $file->rooms()->attach($room->id);

            return redirect()->back()->with(['success'=>'File Added To Room']);

        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }





    }

    public function addFileToMeeting(Request $request)
    {
        try{
            $request->validate([
                'meetings' =>'required',
            ],[
                'meetings.required' =>'Please Select Meeting'
            ]);
            try{
                $decrypt['file'] = decrypt($request->input('file'));
                $decrypt['meetings'] = decrypt($request->input('meetings'));

            }catch (DecryptException $e)
            {

                return redirect()->back()->with(['danger'=>'Some Thing Wrong Please Try Later']);

            }

            $file = Files::findOrFail($decrypt['file']);

            $meeting = Meeting::findOrFail($decrypt['meetings']);

            $file->meetings()->attach($meeting->id);

            return redirect()->back()->with(['success'=>'File Added To Meeting']);
        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }


    }
}
