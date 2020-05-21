<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

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



    public function setAttendeePasswordAttribute($attendee_password)
    {
        $this->attributes['attendee_password'] =  Crypt::encrypt($attendee_password);
    }

    public function setModeratorPasswordAttribute($moderator_password)
    {
        $this->attributes['moderator_password'] =  Crypt::encrypt($moderator_password);
    }

    public function getNameAttribute($value)
    {
        return ucwords($value);
    }






}
