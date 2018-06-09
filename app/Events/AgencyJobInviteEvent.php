<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Job;
use App\User;

class AgencyJobInviteEvent
{
    use InteractsWithSockets, SerializesModels;

    public $job;
    public $client;
    public $agency;
    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( Job $job, User $client, \App\Agency $agency, $message )
    {
        $this->job = $job;
        $this->client = $client;
        $this->agency = $agency;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('job-invite');
    }
}
