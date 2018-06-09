<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class GeneralReferenceRequestEvent
{
    use InteractsWithSockets, SerializesModels;

    public $client;
    public $freelancer;
    public $message;
    public $reference;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( \App\GeneralReference $reference, \App\User $client, \App\User $freelancer, $message = "" )
    {
        $this->reference = $reference;
        $this->client = $client;
        $this->freelancer = $freelancer;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('job-reference-request');
    }
}
