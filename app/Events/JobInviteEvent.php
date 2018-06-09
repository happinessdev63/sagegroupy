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

class JobInviteEvent
{
    use InteractsWithSockets, SerializesModels;

    public $job;
    public $client;
    public $freelancer;
    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( Job $job, User $client, User $freelancer, $message )
    {
        $this->job = $job;
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
        return new PrivateChannel('job-invite');
    }
}
