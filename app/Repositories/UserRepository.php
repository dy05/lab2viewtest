<?php

namespace App\Repositories;

use App\Events\NewMember;
use App\Jobs\SendNotification;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class UserRepository
{
    /**
     * @return Collection|array
     */
    public static function getUsers(): Collection|array
    {
        $query = User::query();
        if ($user = Auth::user()) {
            $query = $query->where('email', '!=', $user->email);
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
//        if ($user->save()) {}
        //        broadcast(new NewMember($user, $request->user()));
        broadcast(new NewMember($authUser, $authUser))->toOthers();
//        SendNotification::dispatch($user, self::getUsers());
        SendNotification::dispatch($authUser, self::getUsers());

        return $user;
    }
}
