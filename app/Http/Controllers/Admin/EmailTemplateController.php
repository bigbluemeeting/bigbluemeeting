<?php

namespace App\Http\Controllers\Admin;

use App\EmailTemplate;
use App\Http\Controllers\Controller;
use App\Room;
use DateTimeZone;
use Fomvasss\LaravelStrTokens\Facades\StrToken;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    //
    public function index()
    {


        $timezonelist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        $pageName ='Email Settings';

        $userEmailTemplate = EmailTemplate::whereUserId(auth()->id())->first();




        $defaultTemplate = EmailTemplate::first();


        return view('admin.email.template',compact('timezonelist','pageName','defaultTemplate','userEmailTemplate'));
    }
    public function store(Request $request)
    {
        $data = $request->only(
            [
                'invite_participants'
                ,'mail_footer',
                'mail_from_name',
                'mail_subject',
                'mail_timezone',
                'mod_mail',
                'mod_mail_footer'

            ]);
        $data['user_id'] = auth()->id();




        $emailTemplate = EmailTemplate::updateOrCreate(['user_id'=>auth()->id()],$data);

        return redirect()->back()->with(['success'=>'Your Emails Settings Saved']);

    }

   public function showTem()
   {
       $user = auth()->user();
       $meeting =  Room::find(25);
//        $input = str_replace('[meeting:start]','[var:start]',$request->input('invite_participants'));
//        $input = str_replace('[meeting:end]','[var:end]',$input);

//
//       $name = 'abcd';
//
//       $str = ['a','b'];
//       $rplc =['$','!'];
//
//       dd(str_replace($str,$rplc,$name));
//       exit;

//       $defaultTemplate = EmailTemplate::first();
//
//       $str = ['[meeting:name]','[user:email]','[meeting:url]'];
//       $rplc = [$meeting->name,$user->email,$meeting->url];
//       dd(str_replace($str,$rplc,$defaultTemplate['mail_invite_participant']));
//       $input = str_replace('[meeting:name]',$meeting->name,$defaultTemplate['mail_invite_participant']);
//
//       $input = str_replace('[user:email]',$user->email,$input);
//
//       $input = str_replace('[meeting:url]','<a href="">'.$meeting->url.'</a>',$input);
//



       return view('admin.email.example');
   }
}
