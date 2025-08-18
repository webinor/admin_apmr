<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserMessage extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user_message ;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user_message)
    {
       $this->user_message = $user_message;
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
        return (new MailMessage)
                    ->line("Salut {$notifiable->employee->last_name}")
                    ->line('Un internaute a laissé un méssage.')
                    ->line("Email : {$this->user_message->email} ")
                    ->line("Message : {$this->user_message->message} ")
                    ->action('Visualiser', url('/admin_panel'))
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
