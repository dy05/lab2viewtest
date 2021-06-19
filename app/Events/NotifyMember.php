<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotifyMember implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private array $user;
    private string $notification_uuid;
    private bool $deleting;

    /**
     * Create a new event instance.
     *
     * @param array $user
     * @param string $notification_uuid
     * @param bool $deleting
     */
    public function __construct(array $user, string $notification_uuid, bool $deleting = false)
    {
        $this->user = $user;
        $this->notification_uuid = $notification_uuid;
        $this->deleting = $deleting;
    }

    /**
     * @return PrivateChannel
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('notify_member');
    }


    public function broadcastAs()
    {
        return 'app.notify_member';
    }

    public function broadcastWith()
    {
        return [
            'user' => $this->user,
            'uuid' => $this->notification_uuid,
            'deleting' => $this->deleting
        ];
    }
}
