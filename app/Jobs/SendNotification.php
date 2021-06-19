<?php

namespace App\Jobs;

use App\Notifications\AlertMessage;
use App\Repositories\UserRepository;
use Exception;
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

    private array $user;
    private string $notification_uuid;
    private bool $deleting;

    /**
     * Create a new job instance.
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
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        $notification = \App\Models\Notification::query()->where('uuid', '=', $this->notification_uuid)->first();
        if (! $notification) {
            throw new Exception('Failed to get Notification');
        }

        $saved = $notification->update(['status' => 1]);
        if (! $saved) {
            throw new Exception('Failed to update Notification');
        }

        Notification::send(
            UserRepository::getUsers([$this->user['id'], $notification->user_id, ...$notification->users]),
            new AlertMessage($this->user, $this->deleting ? $this->user['name'] . ' vient d\'etre supprime' : null)
        );
    }
}
