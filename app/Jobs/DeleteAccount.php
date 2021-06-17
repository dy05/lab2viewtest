<?php

namespace App\Jobs;

use App\Events\NotifyMember;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteAccount implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private User $user;
    private array $authUser;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param array $authUser
     */
    public function __construct(User $user, array $authUser)
    {
        $this->user = $user;
        $this->authUser = $authUser;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->user->toArray();
        if ($this->user->delete()) {
            broadcast(new NotifyMember($user, $this->authUser, true));
        }
    }
}
