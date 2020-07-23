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
use FileUpload\Validator\Simple;
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
//            if ($request->has('meeting'))
//            {
//                $meeting = Meeting::findOrFail($request->input('meeting'));
//            }



            $path = public_path(Files::Folder);
            if(!File::exists($path)) {
                File::makeDirectory($path);
            };

            $validator = new Simple('100M', ['application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/msword',
                'application/vnd.oasis.opendocument.text','application/vnd.openxmlformats-officedocument.presentationml.presentation','application/vnd.oasis.opendocument.presentation','application/vnd.ms-powerpoint','application/pdf','image/jpeg','image/png','image/gif','image/jpg','text/plain']);


            $pathresolver = new \FileUpload\PathResolver\Simple($path);

            // The machine's filesystem

            $filesystem = new \FileUpload\FileSystem\Simple();

            // FileUploader itself
            $fileupload = new \FileUpload\FileUpload($_FILES['files'], $_SERVER);
            $slugGenerator = new \FileUpload\FileNameGenerator\Slug();

            // Adding it all together. Note that you can use multiple validators or none at all
            $fileupload->setPathResolver($pathresolver);
            $fileupload->setFileSystem($filesystem);
            $fileupload->addValidator($validator);
            $fileupload->setFileNameGenerator($slugGenerator);

            // Doing the deed
            list($files, $headers) = $fileupload->processAll();

            // Outputting it, for example like this
            foreach($headers as $header => $value) {
                header($header . ': ' . $value);
            }

            foreach($files as $file){
                //Remember to check if the upload was completed


                if (preg_match('/^.*?(?=\/)/',$file->getMimeType(),$match)) {

                    $type = $match[0].'/'.$file->getExtension();

                }


                if ($file->completed) {

                    // set some data

                    $filename = $file->getFilename();
                    $url = Files::Folder . $filename;


                    $dataArray = [
                        'name' => $filename,
                        'type' => $type,
                        'size' => $file->size,
                        'user_id' => Auth::id(),
                        'upload_date' => Carbon::now(),

                    ];
                    // save data
                    $fileUploaded = Files::create($dataArray);

                    if ($request->has('rooms'))
                    {
                        $fileUploaded->rooms()->attach($room->id);
                    }
//                    if ($request->has('meeting'))
//                    {
//                        $fileUploaded->meetings()->attach($meeting->id);
//                    }



                    // prepare response
                    $data[] = [
                        'size' => Helper::formatBytes($file->size),
                        'id' => encrypt($fileUploaded->id),
                        'name' => $filename,
                        'url' => $url,
                        'type' =>$type,
                        'upload_date' => Carbon::now()->format('Y-m-d h:m A'),
                        'deleteType' => 'DELETE',
                        'setDefaultUrl' =>route('setDefault',$fileUploaded->id),
                        'deleteUrl' => route('files.destroy', $fileUploaded->id),
                    ];



                    // output uploaded file response

                    return response()->json(['files' => $data]);
                }
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
