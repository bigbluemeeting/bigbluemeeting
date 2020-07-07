<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AddParticipantMail extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $emailParams =[];
    public function __construct($emailParams)
    {
        //
        $this->emailParams = $emailParams;
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
            ->subject('Meeting Confirmation For '.$this->emailParams['meeting']['name'])
            ->from(config('global.from_email'),config('global.from_name'))
            ->attach($filename, array('mime' => "text/calendar"))
            ->view('admin.email.add_participants_mail',with(['meetingParams'=>$this->emailParams]));
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
