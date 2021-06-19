<?php

namespace App\Jobs;

use App\Events\NotifyMember;
use App\Models\User;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class DeleteAccount implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $authUserId;
    private User $user;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param int $authUserId
     */
    public function __construct(User $user, int $authUserId)
    {
        $this->user = $user;
        $this->authUserId = $authUserId;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        $userData = $this->user->toArray();
        if ($this->user->delete()) {
            do {
                $uuid = Str::uuid();
            } while (\App\Models\Notification::query()->where('uuid', $uuid)->count());

            $notification = new \App\Models\Notification([
                'uuid' => $uuid,
                'user_id' => $this->authUserId,
            ]);

            if (! $notification->save()) {
                throw new Exception('Failed to create Notification');
            }

            broadcast(new NotifyMember($userData, $uuid, true))->toOthers();
            SendNotification::dispatch(
                $userData,
                $uuid,
                true
            )->delay(now()->addSeconds(20));
        }
    }
}
