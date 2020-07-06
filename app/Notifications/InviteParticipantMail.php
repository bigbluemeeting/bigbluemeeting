<?php

namespace App\Notifications;

use App\Helpers\GenerateICS;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InviteParticipantMail extends Notification implements ShouldQueue
{
    use Queueable;

   protected $url;
   protected $emailParams =[];
    /**
     * Create a new notification instance.
     *
     * @return void
     */
//    $emailParams
    public function __construct($emailParams)
    {


        $this->emailParams = $emailParams;
        $this->url =url('signup/' .$emailParams['meeting_id']. '/' . $emailParams['toEmail']);


    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {



        $filename = "meeting.ics";
        $meetingStartStamp = strtotime( $this->emailParams['meeting']['start_date']. " UTC");

        $dtstart = gmdate('Ymd\THis', $meetingStartStamp);

        $meetingEndStamp = strtotime( $this->emailParams['meeting']['end_date']. " UTC");
        $dtend =  gmdate('Ymd\THis', $meetingEndStamp);
        $todaystamp = gmdate('Ymd\THis');
        $description = strip_tags($this->emailParams['meeting']['meeting_description']);

        $location = " Online At: ".url('/').'/rooms/'.$this->emailParams['meeting']['url'];
        $organizer = "CN=Organizer name:contact@bigbluemeeting.com";


        // ICS
        $mail[0]  = "BEGIN:VCALENDAR";
        $mail[1] = "PRODID:-//Google Inc//Google Calendar 70.9054//EN";
        $mail[2] = "VERSION:2.0";
        $mail[3] = "CALSCALE:GREGORIAN";
        $mail[4] = "METHOD:REQUEST";
        $mail[5] = "BEGIN:VEVENT";
        $mail[6] = "DTSTART:" . $dtstart;
        $mail[7] = "DTEND:" . $dtend;
        $mail[8] = "DTSTAMP:" . $todaystamp;
        $mail[9] = "ORGANIZER;" . $organizer;
        $mail[10] = "CREATED:" . $todaystamp;
        $mail[11] = "DESCRIPTION:" . $description;
        $mail[12] = "LAST-MODIFIED:" . $todaystamp;
        $mail[13] = "LOCATION:" . $location;
        $mail[14] = "SEQUENCE:0";
        $mail[15] = "STATUS:CONFIRMED";
        $mail[16] = "SUMMARY:" . $this->emailParams['meeting']['name'];
        $mail[17] = "TRANSP:OPAQUE";
        $mail[18] = "END:VEVENT";
        $mail[19] = "END:VCALENDAR";
        $mail = implode("\r\n", $mail);
        header("text/calendar");
        file_put_contents($filename, $mail);



        return (new MailMessage)
            ->subject($this->emailParams['mailParams']['subject'])
            ->from('mujeeb@test.com',$this->emailParams['mailParams']['from'])
            ->attach($filename, array('mime' => "text/calendar"))
            ->view('attendee.email.welcome',with(['meetingParams'=>$this->emailParams,'url'=>$this->url]));

    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
