<?php

namespace App\Events;

use App\Models\User;
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
    private array $authUser;
    private bool $deleting;

    /**
     * Create a new event instance.
     *
     * @param array $user
     * @param array $authUser
     * @param bool $deleting
     */
    public function __construct(array $user, array $authUser = [], bool $deleting = false)
    {
        $this->user = $user;
        $this->authUser = $authUser;
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
            'authUser' => $this->authUser,
            'deleting' => $this->deleting
        ];
    }
}
