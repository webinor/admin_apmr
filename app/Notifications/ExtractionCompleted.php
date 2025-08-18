<?php

namespace App\Notifications;

use App\Models\Operations\Slip;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExtractionCompleted extends Notification implements ShouldQueue
{
    use Queueable;

    public $slip;
    public $all_folders;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Slip $slip , $all_folders)
    {
        $this->afterCommit();
        $this->slip=$slip;
        $this->all_folders=$all_folders;
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
        ->subject(__("Extraction terminée"))
                    ->line(__("Ce mail est pour vous informer que l'extraction d'un bordereau s'est terminée avec succès"))
                    ->action('Visualiser le bordereau', url('slip/'.($this->slip->code)))
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

    /**
 * Determine if the notification should be sent.
 *
 * @param  mixed  $notifiable
 * @param  string  $channel
 * @return bool
 */
public function shouldSend($notifiable, $channel)
{
    return  true;// $this->slip->folders->count() == $this->all_folders;
}
}
