<?php

namespace App\Repositories;

use App\Events\NotifyMember;
use App\Jobs\DeleteAccount;
use App\Jobs\SendNotification;
use App\Models\NotificationView;
use App\Models\User;
use App\Notifications\SendMessage;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

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
     * @param string $uuid
     * @param int $authUserId
     * @return NotificationView|false
     * @throws Exception
     */
    public static function addViewedUser(string $uuid, int $authUserId): NotificationView|bool
    {
        /**
         * @var \App\Models\Notification
         */
        $notification = \App\Models\Notification::query()->where('uuid', '=', $uuid)->first();
        if (! $notification) {
            throw new Exception('Failed to get Notification');
        }

        if ((int) $notification->status === 1) {
            return false;
        }

        $viewed = (new NotificationView([
            'user_id' => $authUserId,
            'notification_id' => $notification->id
        ]));

        if (! $viewed->save()) {
            throw new Exception('Failed set Notification as read');
        }

        return $viewed;
    }

    /**
     * @param User $user
     * @param User $authUser
     * @return User
     * @throws Exception
     */
    public function storeUser(User $user, User $authUser): User
    {
        if (! $user->save()) {
            throw new Exception('Failed to create User');
        }
        $userData = $user->toArray();
        Notification::send($user, new SendMessage($userData));
        do {
            $uuid = Str::uuid();
        } while (\App\Models\Notification::query()->where('uuid', $uuid)->count());

        $notification = new \App\Models\Notification([
            'uuid' => $uuid,
            'user_id' => $authUser->id
        ]);

        if (! $notification->save()) {
            throw new Exception('Failed to create Notification');
        }
        broadcast(new NotifyMember($userData, $uuid))->toOthers();
        Bus::chain([
            (new SendNotification(
                $user->toArray(),
                $uuid
            ))->delay(now()->addSeconds(10)),
            (new DeleteAccount($user, $authUser->id))->delay(now()->addSeconds(10))
        ])->dispatch();

        return $user;
    }
}
