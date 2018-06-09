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

class JobInviteResponseEvent
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var Job
     */
    public $job;
    /**
     * @var User
     */
    public $freelancer;
    /**
     * @var User
     */
    public $client;
    public $response;
    public $agencyInvite;
    public $agencyId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( Job $job, User $client, User $freelancer, $response, $agencyInvite = false, $agencyId = 0 )
    {
        $this->job = $job;
        $this->client = $client;
        $this->freelancer = $freelancer;
        $this->response = $response;
        $this->agencyInvite = $agencyInvite;
        $this->agencyId = $agencyId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
