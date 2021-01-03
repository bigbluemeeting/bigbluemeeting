<?php

namespace App\Http\Controllers\Admin;

use App\EmailTemplate;
use App\Http\Controllers\Controller;
use App\User;
use DateTimeZone;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    //
    public function index()
    {

        try{

            $timezonelist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
            $pageName ='Email Settings';
            $userEmailTemplate = EmailTemplate::whereUserId(auth()->id())->first();
            $defaultTemplate = EmailTemplate::first();

            return view('admin.email.template',compact('timezonelist','pageName','defaultTemplate','userEmailTemplate'));

        }catch (\Exception $exception)
        {
            return view('errors.500')->with(['danger'=>$exception->getMessage()]);
        }

    }
    public function store(Request $request)
    {
        try
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

            $emailTemplateplate = EmailTemplate::updateOrCreate(['user_id'=>auth()->id()],$data);

            return redirect()->back()->with(['success'=>'Your Emails Settings Saved']);

        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }

    }

   public function subscribe($mail)
   {
       try{
           $user = User::where('email',$mail)->first();

           $user->send_email = 1;
           $user->save();

           return redirect('admin/dashboard');
       }catch (\Exception $exception)
       {
           return redirect()->back()->with(['danger'=>$exception->getMessage()]);
       }


   }

    public function unSubscribe($mail)
    {

        try{
            $user = User::where('email',$mail)->first();

            $user->send_email = 0;
            $user->save();

            return redirect('admin/dashboard');
        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }


    }
}
