<?php

namespace App\Events;

use App\Agency;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AgencyInviteEvent
{
    use InteractsWithSockets, SerializesModels;

    public $agency;
    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Agency $agency, User $user)
    {
        $this->agency = $agency;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('agency-invite');
    }
}
