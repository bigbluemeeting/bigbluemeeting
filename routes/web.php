<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


use App\bigbluebutton\tests;
use App\Http\Middleware\AjaxCheck;

use BigBlueButton\TestCase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::group(['prefix' => 'admin', 'as' => 'admin::'], function () {

    //Spatie
    Route::resource('/roles', 'Admin\RolesController', [
        'names' => [
            'index'     => 'roles.index',
            'create'    => 'roles.add',
            'edit'      => 'roles.edit',
        ]
    ]);
    Route::post('roles_mass_destroy', ['uses' => 'Admin\RolesController@massDestroy', 'as' => 'roles.mass_destroy']);
    Route::resource('/users', 'Admin\UsersController', [
        'names' => [
            'index'     => 'users.index',
            'create'    => 'users.add',
            'edit'      => 'users.edit',
        ]
    ]);
    Route::post('users_mass_destroy', ['uses' => 'Admin\UsersController@massDestroy', 'as' => 'users.mass_destroy']);
    //Spatie End

    Route::get('/change_password', 'Admin\ChangePasswordController@showChangePasswordForm')->name('change_password');
    Route::patch('/change_password', 'Admin\ChangePasswordController@changePassword')->name('change_password');

    Route::get('/dashboard', 'Admin\DashboardController@index')->name('dashboard');
    Route::get('/meeting-attendees/{meeting}','Admin\MeetingController@meetingAttendees')->name('meetingAttendees');

    Route::resource('/recordings','Admin\RecordingsController');
    Route::resource('/attendees','Admin\AttendeeController');

});
/**
 * Login & SignUp Route
 */
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::get('signup/{meeting?}/{email?}', 'Auth\SignupController@create')->name('signup');
Route::post('register', 'Auth\SignupController@store')->name('register');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/', function () {
    if(Auth::check()) {
        return redirect('admin/dashboard');
    } else {
        return redirect()->route('login');
    }
});
/**
 * Public Routes
 */

Route::get('rooms/invite-meetings','PublicControllers\RoomsController@inviteAttendee')->name('invitedMeetings');
Route::resource('rooms','PublicControllers\RoomsController');
Route::post('/rooms/joins','PublicControllers\RoomsController@join')->name('join');
Route::get('/attendee/joins/{url}','Admin\AttendeeController@joinAttendee')->name('JoinAttendee');
Route::resource('/meetings','Admin\MeetingController');
Route::get('/meetings/joins/{url}','Admin\MeetingController@joinMeeting')->name('JoinMeetings');



/**
 * Routes For Ajax Call
 */
Route::middleware('ajax.check')->group(function ()
{
    Route::post('/rooms/AuthAttendeeJoin','PublicControllers\AttendeesRoomController@authAttendeeJoin')->name('AuthAttendeeJoin');
    Route::post('/rooms/attendeeJoin','PublicControllers\AttendeesRoomController@Join')->name('attendeeJoin');

    Route::post('/attendee/joins','Admin\AttendeeController@joinAttendee')->name('JoinAuthAttendee')->middleware('auth');

});