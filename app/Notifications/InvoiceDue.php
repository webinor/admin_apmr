<?php

namespace App\Notifications;

use App\Models\Misc\Invoice;
use App\Models\Misc\Resource;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceDue extends Notification implements ShouldQueue
{
    use Queueable;

    public $invoice; 

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice =  $invoice;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database'];
    } 

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $start = new Carbon($this->invoice->resource->billing_date);
$end = new Carbon($this->invoice->resource->deadline);
$businessDays = $start->diffInWeekdays($end);
//$businessDays = $start->diffInDays($end);
        return (new MailMessage)
                    ->subject("Rappel d'expiration d'une facture")
                    ->line("La date d'echeance d'une facture sera atteinte dans $businessDays jours")
                    ->action('Notification Action', url('supplier/invoice/'.($this->invoice->resource->code)))
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
            'invoice_id'=> $this->invoice->id,
            'body'=>"La facture Numero 003/SPC/07-24 est sur le point d'expirer",
        ];
    }
}
