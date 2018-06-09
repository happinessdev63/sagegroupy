<?php

namespace App\Listeners;

use App\Events\UserMessageEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notification;

class SendUserMessageNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserMessageEvent  $event
     * @return void
     */
    public function handle(UserMessageEvent $event)
    {
        $receiverNotification = Notification::create( [
            'type'         => "user-message",
            'to_user_id'   => $event->receiver->id,
            'from_user_id' => $event->sender->id,
            'owner_id'     => $event->receiver->id,
            'status'       => 'unread',
            'title'        => "Message From " . $event->sender->name,
            'message'      => $event->message,
            'owner_type'   => 'user'
        ] );

        /**
         * If last message from this user was over 30 minutes ago, send a message notification email.
         */
        $sentMessages = \App\MailLog::where("to_user_id", $event->receiver->id)
            ->where("from_user_id", $event->sender->id)
            ->where("type", "message")
            ->where("created_at",">", date("Y-m-d H:i:s", time() - 60 * 30))->count();

        if ($sentMessages <= 0) {
            $event->receiver->notify(new \App\Notifications\UserReceivedMessage($event->receiver, $event->sender, $event->message));
        }
    }
}
