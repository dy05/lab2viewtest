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
//        if ($user->save()) {
            broadcast(new NewMember($authUser, $authUser))->toOthers();
        //        broadcast(new NewMember($user, $request->user()))->toOthers();
//        }

        return $user;
    }
}
