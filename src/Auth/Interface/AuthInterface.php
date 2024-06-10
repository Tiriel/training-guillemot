<?php

namespace App\Auth\Interface;

interface AuthInterface
{
    public function auth(string $login, string $password): bool;
}
