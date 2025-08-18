<?php

namespace App\Notifications\Common;

use App\Models\Common\Ad;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class newAdNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $user;
    private $ad;

    protected $after_commit = true;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user , Ad $ad)
    {
      //  $this->delay(now()->addMinutes(5));
        $this->user = $user;
        $this->ad = $ad;
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
                    ->subject("Nouvelle annonce")
                    ->line('Hey '.$notifiable->employee->last_name)
                    ->line("une nouvelle annonce a été posté sur ".url('/')." par ".($this->user->fullName()))
                    ->action('Visualiser', url('advert/'.($this->ad->code)))
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
