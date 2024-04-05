<?php

namespace App\Services\Impl;

use App\Services\UserService;

class UserServicesImpl implements UserService
{
    private array $users = [
        "akbar" => "rahasia"
    ];

    function login(string $user, string $password): bool
    {
        if(!isset($this->users[$user])){
            return false;
        }

        $correctPassword = $this->users[$user];
        return $password == $correctPassword;
    }
}
