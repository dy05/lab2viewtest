<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\AlertMessage;
use App\Notifications\SendMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class SendNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private mixed $users;
    private array|User $user;
    /**
     * @var false
     */
    private bool $deleting;

    /**
     * Create a new job instance.
     *
     * @param array|User $user
     * @param mixed|User[] $users
     * @param bool $deleting
     */
    public function __construct(User|array $user, mixed $users, bool $deleting = false)
    {
        $this->users = $users;
        $this->user = $user;
        $this->deleting = $deleting;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $userData = is_array($this->user) ? $this->user : $this->user->toArray();

        if (! $this->deleting && $this->user instanceof User) {
            Notification::send($this->user, new SendMessage($userData));
        }

        Notification::send(
            $this->users,
            new AlertMessage($userData, $this->deleting ? $userData['name'] . ' vient d\'etre supprime' : null)
        );
    }
}
