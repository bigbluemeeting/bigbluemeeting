<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class Meeting extends Model
{
    //


        protected $guarded = [];
        protected $hidden = [
          'pivot'
        ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function getNameAttribute($value)
    {
        return ucwords($value);
    }

    public function files()
    {
        return $this->morphMany(Files::class,'meetingable');
    }



}
