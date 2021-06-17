<?php

namespace App\Models\Concerns;

use App\Jobs\DeleteAccount;

trait MustNotifyAfterDeleted
{
    protected static function userDeleted($user)
    {
        broadcast(new DeleteAccount($user));
    }
}
