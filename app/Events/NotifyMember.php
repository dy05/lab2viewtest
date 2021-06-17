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

    private User $user;
    private ?User $authUser;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param User|null $authUser
     */
    public function __construct(User $user, User $authUser = null)
    {
        $this->user = $user;
        $this->authUser = $authUser;
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
            'id' => $this->user->id,
            'email' => $this->user->email,
        ];
    }
}
