<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ApmrPdfReady implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $exportId;
    public $filePath;
    public $type; // single ou all
    public $name;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    
    public function __construct($exportId, $filePath, $type)
    {
        $this->exportId = $exportId;
        $this->filePath = route('export.download', ['folder'=>$exportId,  'fileName' => basename($filePath)]);// $filePath;
        $this->type = $type;
        $this->name = basename($filePath);
    }


     /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel("export.{$this->exportId}");
    }

    /**
     * The event's broadcast name-
     *
     * @return string
     */
    public function broadcastAs()
    {
        return "ApmrPdfReady";
    }
}
