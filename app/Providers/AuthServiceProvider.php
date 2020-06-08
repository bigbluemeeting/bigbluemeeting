<?php

namespace App\Providers;

use App\Meeting;
use App\Policies\MeetingOwnerPolicy;
use App\Policies\RoomOwnerPolicy;
use App\Room;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Room::class =>RoomOwnerPolicy::class,
        Meeting::class =>MeetingOwnerPolicy::class

    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();


        //
    }
}
