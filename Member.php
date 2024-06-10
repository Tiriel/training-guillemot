<?php

class Member
{
    protected static int $count = 0;

    public function __construct(
        protected string $login,
        protected string $password,
        protected int $age,
    ) {
        static::$count++;
    }

    public function __destruct()
    {
        static::$count--;
    }

    public function auth(string $login, string $password): bool
    {
        return $login === $this->login && $password === $this->password;
    }

    public static function count(): int
    {
        return static::$count;
    }
}
