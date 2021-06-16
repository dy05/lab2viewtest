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
    private User $user;
    /**
     * @var false
     */
    private bool $deleting;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param mixed $users
     * @param bool $deleting
     */
    public function __construct(User $user, mixed $users, bool $deleting = false)
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
        if (! $this->deleting) {
            $this->user->notify(new SendMessage($this->user));
        }

        Notification::send(
            $this->users,
            new AlertMessage($this->user, $this->deleting ? $this->user->name . ' vient d\'etre supprime' : null)
        );
    }
}
