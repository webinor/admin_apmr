<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InviteUserToSetPassword extends Notification implements ShouldQueue
{
    use Queueable;


    private $link;

    public function __construct($link)
    {
        $this->link = $link;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Définissez votre mot de passe')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre compte a été créé. Cliquez sur le bouton ci-dessous pour définir votre mot de passe :')
            ->action('Définir mon mot de passe', $this->link)
            ->line('Si vous n\'êtes pas à l\'origine de cette demande, ignorez cet email.');
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
