<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    //

    protected $guarded = [];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendees()
    {
        return $this->belongsToMany(Attendee::class);
    }

    public function files()
    {
        return $this->belongsToMany(Files::class);
    }
}
