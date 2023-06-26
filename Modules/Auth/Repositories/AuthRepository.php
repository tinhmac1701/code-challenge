<?php

namespace Modules\Auth\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthRepository
{
    public function register(array $data): User
    {
        $data['email_verified_at'] = now(); //quick verified for code challenge

        return User::create($data);
    }
}