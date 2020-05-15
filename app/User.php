<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password', 'admin_unique_key',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }
    public function attendees()
    {
        return $this->hasMany(Attendee::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
