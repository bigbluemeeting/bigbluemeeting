<?php

namespace App\Http\Controllers\Admin;

use App\Files;
use App\Http\Controllers\Controller;
use Croppa;
use File;
//use FileUpload;
use Illuminate\Http\Request;

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
        //

//        $files =$request->file('file');
//        $valid_ext = array('application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/vnd.ms-powerpoint','application/pdf','image/jpeg','image/png','image/gif'
//        );
//
//
//        foreach ($files as $file)
//        {
//
//            $ext =$file->getClientMimeType();
//            if(in_array($ext  , $valid_ext )) {
//
//                /**
//                 * SET All Allowed Files With Extension
//                 *@Var $filesDetails
//                 */
//                $this->filesDetails[]= [$file->getClientOriginalName() => $file->getClientOriginalExtension()];
//
//            }
//        }
//

        dd($_FILES['files']);


//        $path = public_path($this->folder);
//        if(!File::exists($path)) {
//            File::makeDirectory($path);
//        };
//
//        // Simple validation (max file size 2MB and only two allowed mime types)
//        $validator = new FileUpload\Validator\Simple('30M', ['application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/msword','application/vnd.openxmlformats-officedocument.presentationml.presentation','application/vnd.ms-powerpoint','application/pdf','image/jpeg','image/png','image/gif','image/jpg']);
//
//        // Simple path resolver, where uploads will be put
//        $pathresolver = new FileUpload\PathResolver\Simple($path);
//
//        // The machine's filesystem
//        $filesystem = new FileUpload\FileSystem\Simple();
//
//        // FileUploader itself
//        $fileupload = new FileUpload\FileUpload($_FILES['files'], $_SERVER);
//        $slugGenerator = new FileUpload\FileNameGenerator\Slug();
//
//        // Adding it all together. Note that you can use multiple validators or none at all
//        $fileupload->setPathResolver($pathresolver);
//        $fileupload->setFileSystem($filesystem);
//        $fileupload->addValidator($validator);
//        $fileupload->setFileNameGenerator($slugGenerator);
//
//        // Doing the deed
//        list($files, $headers) = $fileupload->processAll();
//
//        // Outputting it, for example like this
//        foreach($headers as $header => $value) {
//            header($header . ': ' . $value);
//        }
//
//        foreach($files as $file){
//            //Remember to check if the upload was completed
//            if ($file->completed) {
//
//                // set some data
//                $filename = $file->getFilename();
//                $url = $this->folder . $filename;
//
//                // save data
//                $picture = Files::create([
//                    'name' => $filename,
//                    'url' => $this->folder . $filename,
//                ]);
//
//                // prepare response
//                $data[] = [
//                    'size' => $file->size,
//                    'name' => $filename,
//                    'url' => $url,
////                    'thumbnailUrl' => url($url),
//                    'deleteType' => 'DELETE',
//                    'deleteUrl' => route('pictures.destroy', $picture->id),
//                ];
//
//                // output uploaded file response
//                return response()->json(['files' => $data]);
//            }
//        }
//        // errors, no uploaded file
//        return response()->json(['files' => $files]);

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
        //
        Croppa::delete($file->url); // delete file and thumbnail(s)
        $file->delete(); // delete db record
        return response()->json([$file->url]);
    }
}
