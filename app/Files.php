<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    //
    const Folder = '/uploads/'; // add slashes for better url handling
    protected  $guarded = [];


    public function meetingable()
    {
        return $this->morphTo();
    }
}
