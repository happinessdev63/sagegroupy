<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class JobViewedEvent
{
    use InteractsWithSockets, SerializesModels;

    public $job;
    public $viewer;
    public $source;
    public $type;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( \App\Job $job, $viewer = null, $source = 'sagegroupy.com', $type='view' )
    {
        $this->job = $job;
        $this->viewer = $viewer;
        $this->source = $source;
        $this->type = $type;

        if (\Auth::user()) {
            $this->viewer = \Auth::user();
        }

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel( 'job-view' );
    }
}
