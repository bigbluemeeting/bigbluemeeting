<?php

namespace App\Http\Controllers\Admin;

use App\Files;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
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
    public $folder = '/uploads/'; // add slashes for better url handling
    public function index()
    {
        // get all pictures

        return view('public.rooms.auth.multi');
        $pictures = Files::all();

        // add properties to pictures
        $pictures->map(function ($picture) {
            $picture['size'] = File::size(public_path($picture['url']));
//            $picture['thumbnailUrl'] =url($picture['url']);
            $picture['deleteType'] = 'DELETE';
            $picture['deleteUrl'] = route('pictures.destroy', $picture->id);
            return $picture;
        });

        // show all pictures
        return response()->json(['files' => $pictures]);
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




        $path = public_path($this->folder);
        if(!File::exists($path)) {
            File::makeDirectory($path);
        };

        // Simple validation (max file size 2MB and only two allowed mime types)
        $validator = new Simple('30M', ['application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/msword',
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
                $url = $this->folder . $filename;

                // save data
                $picture = Files::create([
                    'name' => $filename,
                    'url' =>  $filename,
                    'type' => $file->getMimeType(),
                    'size' => $file->size,
                    'upload_date' => Carbon::now(),
                ]);
                // prepare response
                $data[] = [
                    'size' => Helper::formatBytes($file->size),
                    'name' => $filename,
                    'url' => $url,
                    'type' => $file->getMimeType(),
                    'upload_date' => Carbon::now()->format('Y-m-d h:m A'),
                    'deleteType' => 'DELETE',
                    'deleteUrl' => route('files.destroy', $picture->id),

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


        dd($file);
        //
        $filename  = public_path().'/uploads/im.pdf';
        File::delete($filename);
        $file->delete(); // delete db record

        return response()->json(['file'=>$file->url]);
    }
}
