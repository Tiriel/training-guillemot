<?php

trait AuthTrait
{
    protected string $login;
    protected string $password;

    public function auth(string $login, string $password): bool
    {
        return $login === $this->login && $password === $this->password;
    }
}
