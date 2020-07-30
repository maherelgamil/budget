<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Widget;
use Illuminate\Auth\Access\HandlesAuthorization;

class WidgetPolicy
{
    use HandlesAuthorization;

    public function access(User $user, Widget $widget): bool
    {
        return $widget->user_id === $user->id;
    }
}
