<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendee extends Model
{
    //
    protected  $guarded = [];
    protected  $hidden = [
      'pivot'
    ];

    public function rooms()
    {
        return $this->belongsToMany(Room::class);
    }

}
