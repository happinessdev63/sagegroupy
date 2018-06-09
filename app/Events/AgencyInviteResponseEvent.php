<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Agency;
use App\User;

class AgencyInviteResponseEvent
{
    use InteractsWithSockets, SerializesModels;

    public $agency;
    public $user;
    public $response;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( Agency $agency, User $user, $response )
    {
        $this->agency = $agency;
        $this->user = $user;
        $this->response = $response;
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
