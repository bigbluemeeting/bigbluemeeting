<?php

namespace App\Http\Controllers\Auth;

use App\Attendee;
use App\Http\Controllers\Controller;
use App\Meeting;
use App\Room;
use App\User;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SignupController extends Controller
{
    //

    protected $email;




    public function index()
    {
        return $this->create();

    }
    public function create($meeting=null,$userEmail=null)
    {

        try{
            if (!empty($userEmail))
            {

                try{

                    $userEmail = decrypt($userEmail);

                }catch (DecryptException $e)
                {
                    $userEmail = 'N/A Or Modified';


                }

            }
            $user = User::where('email',$userEmail)->first();


            if ($user) {
                return redirect()->route('login');

            }

            return view('auth.singup',compact('meeting'),compact('meeting','userEmail'));

        }catch (\Exception $exception)
        {
            return view('errors.500')->with(['danger'=>$exception->getMessage()]);
        }



    }


    public function store(Request $request)
    {
//

        try{
            $massage = [
                'name.required' => 'Name Field Required',
                'name.max' =>'Name Field Contains Maximum 50 Characters',
                'username1.required' => 'Username Field Required',
                'username1.max' =>'Username Field Contains Maximum 20 Characters',
                'username1.unique'=>'This Username Already Taken',
                'email.required' => 'Email Field Required',
                'email.unique' =>'This Email Already Taken',
                'email.email' => 'Email Format Not Correct',
                'password1.required' =>'Password Field Required',
                'password1.min' =>'Password Field Contains Minimum 6 Characters'

            ];
            $request->validate([
                'name' => 'required|max:50',
                'username1' => 'required|max:20|unique:users,username',
                'email' => 'required|max:50|unique:users,email|email',
                'password1' => 'required|min:6',

            ],$massage);





            if ($request->has('meeting_id'))
            {
                try{
                    $meeting_id = decrypt($request->input('meeting_id'));
                }catch (DecryptException $e)
                {
                    return redirect()->back()->with(['danger'=>'Something Went Please Check Again']);
                }

            }







            $user = new User;
            $user->name = $request->input('name');
            $user->username = $request->input('username1');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password1'));
            $user->admin_unique_key = $this->_random_str(60);
            $user->save();
            $roles = $request->input('roles') ? $request->input('roles') : ['attendee'];
            $user->assignRole($roles);


            if ($request->has('meeting_id'))
            {

                $attendee  = Attendee::where('email',$request->input('email'))->first();
                $attendee = $attendee->update(['user_id'=>$user->id]);

            }

            Auth::login($user);
            return redirect()->to(route('invitedMeetings'));

        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }


    }


    protected function _random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }
        return $str;
    }
}
