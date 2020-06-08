<?php

namespace App\Policies;

use App\Meeting;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MeetingOwnerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any meetings.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the meeting.
     *
     * @param  \App\User  $user
     * @param  \App\Meeting  $meeting
     * @return mixed
     */
    public function view(User $user, Meeting $meeting)
    {
        //
        return $user->id === $meeting->user_id;

    }

    /**
     * Determine whether the user can create meetings.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the meeting.
     *
     * @param  \App\User  $user
     * @param  \App\Meeting  $meeting
     * @return mixed
     */
    public function update(User $user, Meeting $meeting)
    {
        //
    }

    /**
     * Determine whether the user can delete the meeting.
     *
     * @param  \App\User  $user
     * @param  \App\Meeting  $meeting
     * @return mixed
     */
    public function delete(User $user, Meeting $meeting)
    {
        //
    }

    /**
     * Determine whether the user can restore the meeting.
     *
     * @param  \App\User  $user
     * @param  \App\Meeting  $meeting
     * @return mixed
     */
    public function restore(User $user, Meeting $meeting)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the meeting.
     *
     * @param  \App\User  $user
     * @param  \App\Meeting  $meeting
     * @return mixed
     */
    public function forceDelete(User $user, Meeting $meeting)
    {
        //
    }
}
