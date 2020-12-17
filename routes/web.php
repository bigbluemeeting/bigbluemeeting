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
use Fomvasss\LaravelStrTokens\Facades\StrToken;
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
    Route::get('list/users','Admin\UsersController@userList')->name('userList');
    Route::get('user/roles/{id}','Admin\UsersController@userRoles')->name('userRoles');
    Route::post('users_mass_destroy', ['uses' => 'Admin\UsersController@massDestroy', 'as' => 'users.mass_destroy']);
    //Spatie End

    Route::get('/change_password', 'Admin\ChangePasswordController@showChangePasswordForm')->name('change_password');
    Route::patch('/change_password', 'Admin\ChangePasswordController@changePassword')->name('change_password');

    Route::get('/dashboard', 'Admin\DashboardController@index')->name('dashboard');
    Route::get('/recordings/invited-meeting-recordings','Admin\RecordingsController@invitedRoomsRecordings')->name('invitedRoomsRecordings');
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

    Route::get('meetings/invited-meetings','PublicControllers\Rooms\RoomsController@inviteAttendee')->name('invitedMeetings');
    Route::get('meetings/details/{url}','PublicControllers\Rooms\RoomsController@showDetails')->name('showDetails');
    Route::get('meetings/attendee/{url}','PublicControllers\Rooms\RoomsController@deleteAttendee')->name('deleteAttendee');
    Route::post('/meetings/joins','PublicControllers\Rooms\RoomsController@join')->name('join');
    Route::get('/attendee/joins/{url}','Admin\AttendeeController@joinAttendee')->name('JoinAttendee');
    Route::get('/rooms/joins/{url}','Admin\MeetingController@joinMeeting')->name('JoinMeetings');
    Route::resource('/files','Admin\FilesController');
    Route::get('/files/setDefault/{val}','Admin\FilesController@setDefault')->name('setDefault');
    Route::post('/files/addFilesToRoom','Admin\FilesController@addFileToRoom')->name('addFileToRoom');
    Route::post('/files/addFilesToMeeting','Admin\FilesController@addFileToMeeting')->name('addFileToMeeting');
    Route::get('/rooms/details/{url}','Admin\MeetingController@showDetails')->name('showMeetingDetails');
    Route::resource('/mail','Admin\EmailTemplateController');
    Route::resource('/plans','Admin\PlansController');
});

Route::get('/mail/unsubscribe/{mail}','Admin\EmailTemplateController@unSubscribe')->name('unsubscribe');
Route::get('/mail/subscribe/{mail}','Admin\EmailTemplateController@subscribe')->name('subscribe');

/**
 *  Room <=> MeetingController
 *  Meeting <=> RoomController
 */
Route::get('/meetings/upComingMeetings','PublicControllers\Rooms\RoomsController@upComingMeetings')->name('upComingMeetings');
Route::get('/meetings/pastMeetings','PublicControllers\Rooms\RoomsController@pastMeetings')->name('pastMeetings');
Route::get('/meetings/getInvitedMeetings','PublicControllers\Rooms\RoomsController@getInvitedMeetings')->name('getInvitedMeetings');

Route::resource('/meetings','PublicControllers\Rooms\RoomsController')->except('destroy');
Route::resource('/rooms','Admin\MeetingController');

Route::get('/rooms/{url}',  'PublicControllers\Rooms\RoomsController@show');
Route::post('/meetings/attendee-start-room','PublicControllers\Meetings\AttendeesMeetingController@attendeeStartRoom')->name('attendeeStartRoom');
Route::post('/meetings/attendee-join-moderator','PublicControllers\Meetings\AttendeesMeetingController@attendeeJoinAsModerator')->name('attendeeJoinAsModerator');
Route::get('roomList','Admin\MeetingController@getRoomLists')->name('roomList');
/**
 * Routes For Ajax Call
 */

Route::middleware('ajax.check')->group(function ()
{

    Route::Post('meetingsDelete','PublicControllers\Rooms\RoomsController@destroy')->name('deleteMeetings');
    Route::post('/rooms/AuthAttendeeJoin','PublicControllers\Rooms\AttendeesRoomController@authAttendeeJoin')->name('AuthAttendeeJoin');
    Route::post('/rooms/attendeeJoin','PublicControllers\Rooms\AttendeesRoomController@Join')->name('attendeeJoin');
    Route::post('/attendee/joins','Admin\AttendeeController@joinAttendee')->name('JoinAuthAttendee')->middleware('auth');
    Route::post('/meetings/attendeeJoin','PublicControllers\Meetings\AttendeesMeetingController@joinMeetingAttendee')->name('meetingAttendeesJoin');
    Route::post('/rooms/attendeeJoin','PublicControllers\Rooms\AttendeesRoomController@Join')->name('attendeeJoin');
    Route::post('/meeting-attendees','PublicControllers\Rooms\RoomsController@roomAttendees')->name('roomAttendees');
    Route::post('/meetings/access','PublicControllers\Meetings\AttendeesMeetingController@accessCodeResult')->name('accessCodeResult');

});



//Route::post('/tem','Admin\EmailTemplate@store')->name('emailStore');
//Route::get('/tem',function (){
//
//    $str = StrToken::setText('
//            Example str with tokens for article: "[user:name]([user:id])",
//            Article created at date: [user:created_at],
//            Generated token at: [config:global.app_name], [date:raw]
//            Length: [var:length];
//            Width: [var:width];

//            Price: [var:price]
//        ')
//        ->setDate(\Carbon\Carbon::tomorrow())
//        ->setEntity(\App\User::findorFail(1))
//        ->setVars(['length' => '2.2 m.', 'width' => '3.35 m.'])
//        ->setVar('price', '$13')
//        ->replace();
//    print_r($str);
//});