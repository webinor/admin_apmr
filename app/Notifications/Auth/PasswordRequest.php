<?php

namespace App\Notifications\Auth;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordRequest extends Notification  implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->afterCommit();
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
        $code=$notifiable->employee->code;
        $token =$notifiable->defined_token;// Str::random(40);
        $expire_on = Carbon::parse($notifiable->defined_token_expire_in)->timestamp;// Carbon::now()->addDay()->timestamp;
        return (new MailMessage)
                    ->line('Hello, bien voulir cliquer sur le bouton pour definir votre mot de passe.')
                    ->line('Ce mail expire dans 24h.')
                    ->action('Definir le mot de passe', url("access/user/$code/$token?$expire_on"))
                    ->line('Thank you for using our application!');// return (new MailMessage)
                  //  ->line('Hello, bien voulir cliquer sur le bouton pour completer votre enregistrement.')
                  //  ->action('Definir mot de passe', url('/'))
                  //  ->line('Thank you for using our application!');
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
