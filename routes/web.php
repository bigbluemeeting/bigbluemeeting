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
    Route::get('/recordings/invited-rooms-recordings','Admin\RecordingsController@invitedRoomsRecordings')->name('invitedRoomsRecordings');
    Route::get('/recordings/cache','Admin\RecordingsController@cache');
    Route::resource('/recordings','Admin\RecordingsController');
    Route::resource('/attendees','Admin\AttendeeController');
    Route::post('/recordings/published','Admin\RecordingsController@publishedRecording')->name('publishedRecording');

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
Route::group(['middleware'=>'auth'],function(){

    Route::get('rooms/invited-rooms','PublicControllers\Rooms\RoomsController@inviteAttendee')->name('invitedMeetings');
    Route::get('rooms/details/{url}','PublicControllers\Rooms\RoomsController@showDetails')->name('showDetails');

    Route::post('/rooms/joins','PublicControllers\Rooms\RoomsController@join')->name('join');
    Route::get('/attendee/joins/{url}','Admin\AttendeeController@joinAttendee')->name('JoinAttendee');
    Route::get('/meetings/joins/{url}','Admin\MeetingController@joinMeeting')->name('JoinMeetings');
    Route::resource('/files','Admin\FilesController');
    Route::get('/files/setDefault/{val}','Admin\FilesController@setDefault')->name('setDefault');
    Route::post('/files/addFilesToRoom','Admin\FilesController@addFileToRoom')->name('addFileToRoom');
    Route::post('/files/addFilesToMeeting','Admin\FilesController@addFileToMeeting')->name('addFileToMeeting');
    Route::get('/meetings/details/{url}','Admin\MeetingController@showDetails')->name('showMeetingDetails');
});

Route::resource('rooms','PublicControllers\Rooms\RoomsController');
Route::resource('/meetings','Admin\MeetingController');

Route::get('/rooms/{url}',  'PublicControllers\Rooms\RoomsController@show');
Route::post('/meetings/attendee-start-room','PublicControllers\Meetings\AttendeesMeetingController@attendeeStartRoom')->name('attendeeStartRoom');
Route::post('/meetings/attendee-join-moderator','PublicControllers\Meetings\AttendeesMeetingController@attendeeJoinAsModerator')->name('attendeeJoinAsModerator');


/**
 * Routes For Ajax Call
 */

Route::middleware('ajax.check')->group(function ()
{
    Route::post('/rooms/AuthAttendeeJoin','PublicControllers\Rooms\AttendeesRoomController@authAttendeeJoin')->name('AuthAttendeeJoin');
    Route::post('/rooms/attendeeJoin','PublicControllers\Rooms\AttendeesRoomController@Join')->name('attendeeJoin');
    Route::post('/attendee/joins','Admin\AttendeeController@joinAttendee')->name('JoinAuthAttendee')->middleware('auth');
    Route::post('/meetings/attendeeJoin','PublicControllers\Meetings\AttendeesMeetingController@joinMeetingAttendee')->name('meetingAttendeesJoin');
    Route::post('/rooms/attendeeJoin','PublicControllers\Rooms\AttendeesRoomController@Join')->name('attendeeJoin');
    Route::post('/meeting-attendees','PublicControllers\Rooms\RoomsController@roomAttendees')->name('roomAttendees');
    Route::post('/meetings/access','PublicControllers\Meetings\AttendeesMeetingController@accessCodeResult')->name('accessCodeResult');

});

