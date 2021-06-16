<?php

namespace App\Repositories;

use App\Events\NewMember;
use App\Models\User;

class UserRepository
{
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

        return $user;
    }
}
