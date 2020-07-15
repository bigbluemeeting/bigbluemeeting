<?php

use App\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $email =EmailTemplate::create([

            'invite_participants' => 'You are invited to attend an online meeting by [user:email].What? An online meeting titled new [meeting:name].

When? The meetings will start at [meeting:start] (UTC) and will end at [meeting:end].

You may join the meeting, respond to the invitation, and get local time by going to the following URL:[meeting:url]',

            'mail_footer' =>'If you need to get a hold of the meeting coordinator for any reason, simply reply to this e-mail, the reply will be addressed to his or her e-mail.

Thanks.',
            'mail_from_name' =>'Big Blue Button',
            'mail_subject' =>'Online meeting invite titled [meeting:name] from [user:email]',
            'mail_timezone' =>'UTC',
            'mod_mail' =>'You have created a meeting and added a number of participants to it.We have sent e-mail invites to everyone you have invited.   

If they reply to any of the invites via e-mail their replies will be sent to you.

Fifteen (15) minutes before the meeting login to Big Blue Manager at  to start and join the meeting [site:url].',

            'mod_mail_footer'=>'If you do not wish to receive ANY messages from us including any future invites please click this link: [unsubscribe:link]

If you have asked us to stopped sending you mail but now have changed your mind click here: [subscribe:link]',
        ]);
    }
}
