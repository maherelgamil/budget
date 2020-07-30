<?php

namespace App\Actions;

use App\Models\User;
use App\Models\Widget;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateUserAction
{
    public function execute(string $name, string $email, string $password): User
    {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'verification_token' => Str::random(100)
        ]);

        Widget::create([
            'user_id' => $user->id,
            'type' => 'balance',
            'sorting_index' => 0,
            'settings' => (object) []
        ]);

        Widget::create([
            'user_id' => $user->id,
            'type' => 'spent',
            'sorting_index' => 1,
            'settings' => (object) ['period' => 'today']
        ]);

        Widget::create([
            'user_id' => $user->id,
            'type' => 'spent',
            'sorting_index' => 2,
            'settings' => (object) ['period' => 'this_month']
        ]);

        return $user;
    }
}
