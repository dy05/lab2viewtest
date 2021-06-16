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

class NewMember implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var User
     */
    public $user;
    /**
     * @var int|null
     */
    public $authUser;

    /**
     * NewMember constructor.
     * @param User $user
     * @param User|null $authUser
     */
    public function __construct(User $user, User $authUser = null)
    {
        $this->user = $user;
        $this->authUser = $authUser;
    }

    /**
     * @return PresenceChannel
     */
    public function broadcastOn(): PresenceChannel
    {
        return new PresenceChannel('connected_users');
    }

    public function broadcastAs()
    {
        return 'app.new_member';
    }

    public function broadcastWith()
    {
        return [
            'message' => 'Ok Ok ' . $this->authUser->name
        ];
    }
}
