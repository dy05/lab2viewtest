<?php

namespace App\Models\Concerns;


use App\Jobs\DeleteAccount;
use App\Jobs\SendNotification;
use App\Notifications\SendMessage;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Notification;

trait MustNotifyAfterCreated
{
    protected static function userCreated($user)
    {
        $userData = $user->toArray();
        Notification::send($user, new SendMessage($userData));
        SendNotification::dispatch($userData, UserRepository::getUsers([$user->id]));
        DeleteAccount::dispatch($user)->delay(now()->addMinutes(10));
    }
}
