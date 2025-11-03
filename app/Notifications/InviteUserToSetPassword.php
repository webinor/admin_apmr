<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\View;

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
        // Variables passées à la vue Blade
        $data = [
            'name' => $notifiable->name,
            'link' => $this->link,
            'year' => date('Y'),
        ];

        // On rend la vue Blade comme contenu HTML
        $html = View::make('emails.invite-user', $data)->render();

        return (new MailMessage)
            ->subject('Définissez votre mot de passe')
            ->view('emails.invite-user', $data);
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
