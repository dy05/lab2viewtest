<?php

namespace App\Repositories;

use App\Events\NotifyMember;
use App\Jobs\DeleteAccount;
use App\Models\User;
use App\Notifications\SendMessage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class UserRepository
{
    /**
     * @param int[] $except_users
     * @return Collection|array
     */
    public static function getUsers(array $except_users = []): Collection|array
    {
        $query = User::query();
        if ($user = Auth::user()) {
            $query = $query->where('email', '!=', $user->email);
        }

        if (count($except_users)) {
            $query = $query->whereNotIn('id', $except_users);
        }

        return $query->get();
    }

    /**
     * @param User $user
     * @param User $authUser
     * @return User
     */
    public function storeUser(User $user, User $authUser): User
    {
        if ($user->save()) {
            $userData = $user->toArray();
            Notification::send($user, new SendMessage($userData));
            broadcast(new NotifyMember($userData, $authUser->toArray()));
            DeleteAccount::dispatch($user, $authUser->toArray())->delay(now()->addMinutes(1));
        }

        return $user;
    }
}
