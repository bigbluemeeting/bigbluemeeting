<?php

namespace App\Http\Controllers\Admin;

use App\Files;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Room;
use Carbon\Carbon;
use Croppa;

//use FileUpload;
use FileUpload\Validator\Simple;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

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
        $files  =  Files::orderBy('id','DESC')->paginate(10);


        $pageName = 'File Upload';
        return view('admin.files.index',compact('files','pageName'));

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

        if ($request->has('meeting'))
        {
            $room = Room::findorFail($request->input('meeting'));
        }

        $path = public_path(Files::Folder);
        if(!File::exists($path)) {
            File::makeDirectory($path);
        };

        // Simple validation (max file size 2MB and only two allowed mime types)
        $validator = new Simple('100M', ['application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/msword',
            'application/vnd.oasis.opendocument.text','application/vnd.openxmlformats-officedocument.presentationml.presentation','application/vnd.oasis.opendocument.presentation','application/vnd.ms-powerpoint','application/pdf','image/jpeg','image/png','image/gif','image/jpg','text/plain']);

//        'application/CDFV2','application/x-rar'
        // Simple path resolver, where uploads will be put

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

            if ($file->completed) {

                // set some data
                $filename = $file->getFilename();
                $url = Files::Folder . $filename;


                $dataArray = [
                    'name' => $filename,
                    'type' => $file->getMimeType(),
                    'size' => $file->size,
                    'upload_date' => Carbon::now(),

                ];
                // save data
                if ($request->has('meeting'))
                {
                    $fileUploaded = $room->files()->create($dataArray);
                }
                else{


                    $fileUploaded = Files::create($dataArray);




                }



                // prepare response
                $data[] = [
                    'size' => Helper::formatBytes($file->size),
                    'name' => $filename,
                    'url' => $url,
                    'type' => $file->getMimeType(),
                    'upload_date' => Carbon::now()->format('Y-m-d h:m A'),
                    'deleteType' => 'DELETE',
                    'setDefaultUrl' =>route('setDefault',$fileUploaded->id),
                    'deleteUrl' => route('files.destroy', $fileUploaded->id),
                ];



                // output uploaded file response

                return response()->json(['files' => $data]);
            }
        }
//        return redirect()->back();
        // errors, no uploaded file
        return response()->json(['files' => $files]);

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

        $filename  = public_path().Files::Folder.$file->name;
        File::delete($filename);
        $file->delete(); // delete db record
        return response()->json(['file'=>'Deleted']);
    }

    public function setDefault($id)
    {
        Files::where('setDefault',1)->update(['setDefault'=>0]);

        $files = Files::findOrFail($id);
        $files->setDefault = 1;
        $files->save();
        return redirect()->back();

    }
}
