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
    private array $user;
    /**
     * @var false
     */
    private bool $deleting;

    /**
     * Create a new job instance.
     *
     * @param array $user
     * @param mixed|User[] $users
     * @param bool $deleting
     */
    public function __construct(array $user, mixed $users, bool $deleting = false)
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
        Notification::send(
            $this->users,
            new AlertMessage($this->user, $this->deleting ? $this->user['name'] . ' vient d\'etre supprime' : null)
        );
    }
}
