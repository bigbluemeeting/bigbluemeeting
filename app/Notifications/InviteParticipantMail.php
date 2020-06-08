<?php

namespace App\Notifications;

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
//        "'.$this->emailParams['meetingName'].'" from "'.$this->emailParams['fromEmail'].'"
        return (new MailMessage)
            ->from($this->emailParams['from']['email'])
            ->greeting('Meeting Invitation')
            ->line('You are  Invited for this meeting '.$this->emailParams['meetingName'].' from '.$this->emailParams['from']['name'].'')
            ->action('Sign Up For Join Meeting',url($this->url))
            ->line('Thank you for using our application!');
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
