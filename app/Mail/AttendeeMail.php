<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AttendeeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $toEmail,$formEmail,$meeting_id,$meeting_name;
    protected $name;
    public function __construct($name)
    {

        $this->toEmail = $name['toEmail'];
        $this->formEmail=$name['fromEmail'];
        $this->meeting_id=$name['meeting_id'];
        $this->meeting_name=$name['meeting_name'];

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {


        return $this->from($this->formEmail)->view('attendee.email.welcome')->with([
            'meeting'=>$this->meeting_name,
            'meeting_id'=>$this->meeting_id,
            'to' => $this->toEmail,
            'from'=>$this->formEmail
        ]);
    }
}
