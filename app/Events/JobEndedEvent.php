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

class JobEndedEvent
{
    use InteractsWithSockets, SerializesModels;

    public $job;
    public $client;
    public $freelancer;
    public $agency;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Job $job, User $client, User $freelancer, \App\Agency $agency = null)
    {
        $this->job = $job;
        $this->client = $client;
        $this->freelancer = $freelancer;
        $this->agency = $agency;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('job-ended');
    }
}
