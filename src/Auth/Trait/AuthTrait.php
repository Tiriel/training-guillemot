<?php

namespace App\Auth\Trait;

use App\Auth\Exception\AuthException;

trait AuthTrait
{
    protected string $login;
    protected string $password;

    public function auth(string $login, string $password): bool
    {
        if ($login !== $this->login || $password !== $this->password) {
            throw new AuthException('Wrong Credentials');
        }

        return true;
    }
}
